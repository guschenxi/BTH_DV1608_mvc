<?php

namespace App\Repository;

use App\Entity\Roundlog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Roundlog>
 *
 * @method Roundlog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Roundlog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Roundlog[]    findAll()
 * @method Roundlog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoundlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Roundlog::class);
    }

//    /**
//     * @return Roundlog[] Returns an array of Roundlog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Roundlog
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
