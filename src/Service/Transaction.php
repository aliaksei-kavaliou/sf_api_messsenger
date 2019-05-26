<?php declare(strict_types = 1);

namespace App\Service;

use App\Enum\CurrencyEnum;
use App\Repository\TransactionRepository;

class Transaction
{
    /**
     * @var TransactionRepository
     */
    private $repository;

    /**
     * @var int
     */
    private $transactionDayLimit;

    /**
     * @var int
     */
    private $transactionHourLimit;

    /**
     * Transaction constructor.
     *
     * @param TransactionRepository $repository
     * @param int                   $transactionDayLimit
     * @param int                   $transactionHourLimit
     */
    public function __construct(TransactionRepository $repository, int $transactionDayLimit, int $transactionHourLimit)
    {
        $this->repository = $repository;
        $this->transactionDayLimit = $transactionDayLimit;
        $this->transactionHourLimit = $transactionHourLimit;
    }

    /**
     * @param int          $userId
     * @param CurrencyEnum $currency
     *
     * @return bool
     */
    public function transactionAllowed(int $userId, CurrencyEnum $currency): bool
    {
        return !$this->repository->transactionHourLimitExceeded($userId, $this->transactionHourLimit)
            && !$this->repository->dailyLimitExceeded($userId, $this->transactionDayLimit, $currency);
    }
}
