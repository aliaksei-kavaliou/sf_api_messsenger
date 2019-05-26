<?php declare(strict_types = 1);

namespace App\Messenger\Message;

use App\Messenger\MessageInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PostTransaction
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $userId;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $details;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=20)
     */
    private $receiverAccount;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=128)
     */
    private $receiverName;

    /**
     * @Assert\NotBlank()
     * @var float
     */
    private $amount;

    /**
     * @var string
     * @Assert\Choice(choices={"EUR", "USD"})
     */
    private $currency;

    /**
     * PostTransaction constructor.
     *
     * @param int    $userId
     * @param string $details
     * @param string $receiverAccount
     * @param string $receiverName
     * @param float  $amount
     * @param string $currency
     */
    public function __construct(
        int $userId,
        string $details,
        string $receiverAccount,
        string $receiverName,
        float $amount,
        string $currency
    ) {
        $this->userId = $userId;
        $this->details = $details;
        $this->receiverAccount = $receiverAccount;
        $this->receiverName = $receiverName;
        $this->amount = $amount;
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
    public function getReceiverAccount(): string
    {
        return $this->receiverAccount;
    }

    /**
     * @return string
     */
    public function getReceiverName(): string
    {
        return $this->receiverName;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }
}
