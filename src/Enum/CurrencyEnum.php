<?php declare(strict_types = 1);

namespace App\Enum;

class CurrencyEnum extends AbstractEnum
{
    public const USD = "USD";
    public const EUR = "EUR";

    /**
     * @return string
     */
    protected function getEnumName(): string
    {
        return 'Currency';
    }
}
