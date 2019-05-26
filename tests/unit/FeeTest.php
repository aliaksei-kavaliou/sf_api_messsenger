<?php declare(strict_types = 1);

namespace App\Tests\unit;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Service\Fee;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FeeTest extends KernelTestCase
{
    public function testApplyFee(): void
    {
        /** @var TransactionRepository $repo */
        $repo = $this->prophesize(TransactionRepository::class);
        /** @noinspection PhpStrictTypeCheckingInspection */
        $repo->countDailyTransactions(Argument::type("int"))->will(
            function ($args) {
                if (1 === $args[0]) {
                    return 90;
                }

                return 100;
            }
        );

        $service = new Fee(10, 5, 100, $repo->reveal());
        $transaction = new Transaction();
        $transaction->setAmount(20)->setUserId(2);

        $this->assertNull($transaction->getFee());
        $service->applyFee($transaction);
        $this->assertEquals(1, $transaction->getFee());

        $transaction2 = new Transaction();
        $transaction2->setUserId(1)->setAmount(20);
        $service->applyFee($transaction2);
        $this->assertEquals(2, $transaction2->getFee());
    }
}
