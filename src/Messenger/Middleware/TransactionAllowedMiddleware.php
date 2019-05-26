<?php declare(strict_types = 1);

namespace App\Messenger\Middleware;

use App\Enum\CurrencyEnum;
use App\Exceptions\TransactionNotAllowedException;
use App\Service\Transaction;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class TransactionAllowedMiddleware implements MiddlewareInterface
{
    /**
     * @var Transaction
     */
    private $transactionService;

    /**
     * TransactionAllowedMiddleware constructor.
     *
     * @param Transaction $transactionService
     */
    public function __construct(Transaction $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param Envelope       $envelope
     * @param StackInterface $stack
     *
     * @return Envelope
     * @throws TransactionNotAllowedException
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $stamp = $envelope->last(TransactionAllowedStamp::class);
        $isReceivedMessage = null !== $envelope->last(ReceivedStamp::class);

        if (!$isReceivedMessage || !$stamp) {
            return $stack->next()->handle($envelope, $stack);
        }

        $teamId = $stamp->getTeamId();
        if (!$this->transactionService($stamp->getUserId(), new CurrencyEnum($stamp->getCurrency()))) {
            throw new TransactionNotAllowedException();
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
