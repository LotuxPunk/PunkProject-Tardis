<?php

namespace App\Repository;

use App\Entity\Datapack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Datapack|null find($id, $lockMode = null, $lockVersion = null)
 * @method Datapack|null findOneBy(array $criteria, array $orderBy = null)
 * @method Datapack[]    findAll()
 * @method Datapack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatapackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Datapack::class);
    }

    // /**
    //  * @return Datapack[] Returns an array of Datapack objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Datapack
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
