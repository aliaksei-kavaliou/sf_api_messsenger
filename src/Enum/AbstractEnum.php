<?php declare(strict_types = 1);

namespace App\Enum;

use App\Exception\InvalidEnumExceptions;

abstract class AbstractEnum implements EnumInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var array
     */
    private static $constCacheArray = [];

    /**
     * AbstractEnum constructor.
     *
     * @param string $value
     *
     * @throws InvalidEnumException
     * @throws \ReflectionException
     */
    public function __construct(string $value)
    {
        if (!\in_array($value, static::getAvailableValues())) {
            throw new InvalidEnumException($this->getEnumName());
        }
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function getAvailableValues(): array
    {
        $calledClass = static::class;
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = array_values($reflect->getConstants());
        }

        return self::$constCacheArray[$calledClass];
    }

    /**
     * @return string
     */
    abstract protected function getEnumName(): string;
}
