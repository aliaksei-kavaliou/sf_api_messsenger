<?php declare(strict_types = 1);

namespace App\Enum;

class TransactionStatusEnum extends AbstractEnum
{
    public const PENDING = "PENDING";
    public const CONFIRMED = "CONFIRMED";
    public const COMPLETED = "COMPLETED";
    public const FAILED = "FAILED";

    /**
     * @return string
     */
    protected function getEnumName(): string
    {
        return 'TransactionStatus';
    }
}
