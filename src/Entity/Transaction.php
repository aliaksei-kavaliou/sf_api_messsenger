<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Enum\TransactionStatusEnum;
use App\Enum\CurrencyEnum;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $fee;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $details;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $receiverAccount;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $receiverName;

    /**
     * @var CurrencyEnum
     * @ORM\Column(type="currency_type")
     */
    private $currency;

    /**
     * @var TransactionStatusEnum
     * @ORM\Column(type="transaction_status_type")
     */
    private $status;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return Transaction
     */
    public function setUserId(int $userId): Transaction
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return Transaction
     */
    public function setAmount(float $amount): Transaction
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getFee(): ?float
    {
        return $this->fee;
    }

    /**
     * @param float $fee
     *
     * @return Transaction
     */
    public function setFee(float $fee): Transaction
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @param string $details
     *
     * @return Transaction
     */
    public function setDetails(string $details): Transaction
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @return string
     */
    public function getReceiverAccount(): string
    {
        return $this->receiverAccount;
    }

    /**
     * @param string $receiverAccount
     *
     * @return Transaction
     */
    public function setReceiverAccount(string $receiverAccount): Transaction
    {
        $this->receiverAccount = $receiverAccount;

        return $this;
    }

    /**
     * @return string
     */
    public function getReceiverName(): string
    {
        return $this->receiverName;
    }

    /**
     * @param string $receiverName
     *
     * @return Transaction
     */
    public function setReceiverName(string $receiverName): Transaction
    {
        $this->receiverName = $receiverName;

        return $this;
    }

    /**
     * @return CurrencyEnum
     */
    public function getCurrency(): CurrencyEnum
    {
        return $this->currency;
    }

    /**
     * @param CurrencyEnum $currency
     *
     * @return Transaction
     */
    public function setCurrency(CurrencyEnum $currency): Transaction
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return TransactionStatusEnum
     */
    public function getStatus(): TransactionStatusEnum
    {
        return $this->status;
    }

    /**
     * @param TransactionStatusEnum $status
     *
     * @return Transaction
     */
    public function setStatus(TransactionStatusEnum $status): Transaction
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     *
     * @return Transaction
     */
    public function setCreated(\DateTime $created): Transaction
    {
        $this->created = $created;

        return $this;
    }
}
