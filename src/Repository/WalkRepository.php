<?php

namespace App\Repository;

use App\Entity\Walk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Walk>
 */
class WalkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Walk::class);
    }

    //    /**
    //     * @return Walk[] Returns an array of Walk objects
    //     */
    public function findNext(int $limit = 10): array
    {
        return $this->createQueryBuilder('walk')
            ->andWhere('walk.date >= :now')
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('walk.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Walk
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
