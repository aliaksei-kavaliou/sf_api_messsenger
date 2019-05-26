<?php declare(strict_types = 1);

namespace App\Messenger\Handler;

use App\Entity\Transaction;
use App\Enum\CurrencyEnum;
use App\Enum\TransactionStatusEnum;
use App\Messenger\Message\PostTransaction;
use App\Service\Fee;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PostTransactionHandler implements MessageHandlerInterface
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var Fee
     */
    private $fee;

    /**
     * PostTransactionHandler constructor.
     *
     * @param RegistryInterface $registry
     * @param Fee      $fee
     */
    public function __construct(RegistryInterface $registry, Fee $fee)
    {
        $this->registry = $registry;
        $this->fee = $fee;
    }

    /**
     * @param PostTransaction $message
     */
    public function __invoke(PostTransaction $message)
    {
        $em = $this->registry->getManager();
        $transaction = new Transaction();
        $transaction->setAmount($message->getAmount())
                    ->setCreated(new \DateTime())
                    ->setUserId($message->getUserId())
                    ->setReceiverAccount($message->getReceiverAccount())
                    ->setReceiverName($message->getReceiverName())
                    ->setStatus(new TransactionStatusEnum(TransactionStatusEnum::PENDING))
                    ->setCurrency(new CurrencyEnum($message->getCurrency()))
                    ->setDetails($message->getDetails());

        $this->fee->applyFee($transaction);

        $em->persist($transaction);
        $em->flush();
    }
}
