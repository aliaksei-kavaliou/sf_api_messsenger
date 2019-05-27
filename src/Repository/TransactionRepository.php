<?php declare(strict_types = 1);

namespace App\Repository;

use App\Enum\CurrencyEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * @param int $userId
     * @param int $limit
     *
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function hourLimitExceeded(int $userId, int $limit): bool
    {
        $qb = $this->createQueryBuilder('t')
            ->select('count(t.id)')
                   ->andWhere('t.userId = :user_id')
                   ->andWhere('t.created >= :date')
                   ->setParameters(
                       [
                           'user_id' => $userId,
                           'date' => new \DateTime('-1hour')
                       ]
                   );

       return $limit < (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param int          $userId
     * @param int          $limit
     * @param CurrencyEnum $currency
     *
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function dailyLimitExceeded(int $userId, int $limit, CurrencyEnum $currency): bool
    {
        $qb = $this->createQueryBuilder('t')
            ->select('sum(t.amount) + sum(t.fee)')
            ->where('t.userId = :user_id')
            ->andWhere('t.created >= :date')
            ->andWhere('t.currency = :currency')
            ->setParameters(
                [
                    'user_id' => $userId,
                    'currency' => $currency,
                    'date' => (new \DateTime())->format('Y-m-d')
                ]
            );

        return $limit <= (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param int $userId
     *
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countDailyTransactions(int $userId): int
    {
        $qb = $this->createQueryBuilder('t')
                   ->select('count(t.id)')
                   ->andWhere('t.userId = :user_id')
                   ->andWhere('t.created >= :date')
                   ->setParameters(
                       [
                           'user_id' => $userId,
                           'date' => (new \DateTime())->format('Y-m-d')
                       ]
                   );

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
