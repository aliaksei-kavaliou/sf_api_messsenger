<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;

class Fee
{
    /**
     * @var int
     */
    private $feePercentage;

    /**
     * @var int
     */
    private $feePercentageLight;

    /**
     * @var int
     */
    private $feeLightMin;

    /**
     * @var TransactionRepository
     */
    private $repository;

    /**
     * Fee constructor.
     *
     * @param int                   $feePercentage
     * @param int                   $feePercentageLight
     * @param int                   $feeLightMin
     * @param TransactionRepository $repository
     */
    public function __construct(
        int $feePercentage,
        int $feePercentageLight,
        int $feeLightMin,
        TransactionRepository $repository
    ) {
        $this->feePercentage = $feePercentage;
        $this->feePercentageLight = $feePercentageLight;
        $this->feeLightMin = $feeLightMin;
        $this->repository = $repository;
    }

    /**
     * Applies fee to transaction
     *
     * @param Transaction $transaction
     */
    public function applyFee(Transaction $transaction): void
    {
        $postedTransactions = $this->repository->countDailyTransactions($transaction->getUserId());
        $feePercentage = $postedTransactions >= $this->feeLightMin ? $this->feePercentageLight : $this->feePercentage;
        $fee = $transaction->getAmount() * $feePercentage / 100;
        $transaction->setFee($fee);
    }
}
