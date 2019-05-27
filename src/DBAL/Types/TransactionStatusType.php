<?php declare(strict_types = 1);

namespace App\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Enum\TransactionStatusEnum;

class TransactionStatusType extends Type
{
    public const NAME = 'transaction_status_type';

    /**
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'VARCHAR(255)';
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return TransactionStatusEnum
     * @throws \App\Exception\InvalidEnumException
     * @throws \ReflectionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): TransactionStatusEnum
    {
        return new TransactionStatusEnum($value);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof TransactionStatusEnum) {
            $value = $value->getValue();
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param AbstractPlatform $platform
     *
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
