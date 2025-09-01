<?php

namespace App\Repository;

use App\Entity\Dog;
use App\Entity\User;

use App\Entity\WalkRegistration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WalkRegistration>
 */
class WalkRegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WalkRegistration::class);
    }


    public function findNextWalkByDog(Dog $dog, User $user): ?WalkRegistration
    {
        return $this->createQueryBuilder('wr')
            ->join('wr.walk', 'w')
            ->join('wr.dog', 'd')
            ->andWhere('wr.dog = :dog')
            ->andWhere('d.user = :user')
            ->andWhere('w.date >= :now')
            ->setParameter('dog', $dog)
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTimeImmutable())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
//    /**
//     * @return WalkRegistration[] Returns an array of WalkRegistration objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WalkRegistration
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
