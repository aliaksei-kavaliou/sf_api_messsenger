<?php declare(strict_types = 1);

namespace App\Enum;

interface EnumInterface
{
    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @return array
     */
    public static function getAvailableValues(): array;
}
