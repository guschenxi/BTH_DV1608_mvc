<?php

namespace App\Repository;

use App\Entity\Gamelog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gamelog>
 *
 * @method Gamelog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gamelog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gamelog[]    findAll()
 * @method Gamelog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamelogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gamelog::class);
    }

    //    /**
    //     * @return Gamelog[] Returns an array of Gamelog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Gamelog
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
