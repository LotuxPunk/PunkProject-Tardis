<?php

namespace App\Repository;

use App\Entity\AssetCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssetCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetCategory[]    findAll()
 * @method AssetCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssetCategory::class);
    }

    // /**
    //  * @return AssetCategory[] Returns an array of AssetCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssetCategory
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
