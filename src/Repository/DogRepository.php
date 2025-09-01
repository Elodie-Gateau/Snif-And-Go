<?php

namespace App\Repository;

use App\Entity\Dog;
use App\Entity\User;
use App\Entity\Walk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dog>
 */
class DogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dog::class);
    }


    public function findAvailableForWalk(User $user, Walk $walk): array
    {
        return $this->createQueryBuilder('dog')
            ->andWhere('dog.user = :user')
            ->andWhere('dog NOT IN (
            SELECT registeredDog
            FROM App\Entity\WalkRegistration wr
            JOIN wr.dog registeredDog
            WHERE wr.walk = :walk
        )')
            ->setParameter('user', $user)
            ->setParameter('walk', $walk)
            ->orderBy('dog.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByUser($user): array
    {
        return $this->createQueryBuilder('dog')
            ->andWhere('dog.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Dog[] Returns an array of Dog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
}
