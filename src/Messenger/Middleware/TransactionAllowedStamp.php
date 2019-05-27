<?php declare(strict_types = 1);

namespace App\Messenger\Middleware;

use Symfony\Component\Messenger\Stamp\StampInterface;

class TransactionAllowedStamp implements StampInterface
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $currency;

    /**
     * TransactionAllowedStamp constructor.
     *
     * @param int    $userId
     * @param string $currency
     */
    public function __construct(int $userId, string $currency)
    {
        $this->userId = $userId;
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
