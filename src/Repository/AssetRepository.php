<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Asset;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Asset|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asset|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asset[]    findAll()
 * @method Asset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asset::class);
    }

    /**
     * @return Asset[] Returns an array of Asset objects
     */

    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.author = :author')
            ->setParameter('author', $user)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Asset
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
