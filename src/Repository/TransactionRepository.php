<?php declare(strict_types = 1);

namespace App\Repository;

use App\Enum\CurrencyEnum;
use App\Enum\TransactionStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Accessor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accessor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accessor[]    findAll()
 * @method Accessor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Accessor::class);
    }

    /**
     * @param int $userId
     * @param int $limit
     *
     * @return bool
     */
    public function hourLimitExceeded(int $userId, int $limit): bool
    {
        $qb = $this->createQueryBuilder()
            ->select('count(id)')
                   ->andWhere('userId = :user_id')
                   ->andWhere('created >= :date')
                   ->setParameters(
                       [
                           'user_id' => $userId,
                           'date' => new \DateTime('-1hour')
                       ]
                   );

       return $limit < $qb->getQuery()->getScalarResult();
    }

    /**
     * @param int          $userId
     * @param int          $limit
     * @param CurrencyEnum $currency
     *
     * @return bool
     */
    public function dailyLimitExceeded(int $userId, int $limit, CurrencyEnum $currency): bool
    {
        $qb = $this->createQueryBuilder()
            ->select('sum(amount) + sum(fee)')
            ->where('userId = :user_id')
            ->andWhere('created >= :date')
            ->andWhere('currency = :currency')
            ->setParameters(
                [
                    'user_id' => $userId,
                    'currency' => $currency,
                    'date' => (new \DateTime())->format('Y-m-d')
                ]
            );

        return $limit <= $qb->getQuery()->getScalarResult();
    }

    /**
     * @param int $userId
     *
     * @return int
     */
    public function countDailyTransactions(int $userId): int
    {
        $qb = $this->createQueryBuilder()
                   ->select('count(id)')
                   ->andWhere('userId = :user_id')
                   ->andWhere('created >= :date')
                   ->setParameters(
                       [
                           'user_id' => $userId,
                           'date' => (new \DateTime())->format('Y-m-d')
                       ]
                   );

        return $qb->getQuery()->getScalarResult();
    }
}
