<?php

namespace App\Repository;

use App\Entity\FeaturedImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FeaturedImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeaturedImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeaturedImage[]    findAll()
 * @method FeaturedImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeaturedImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeaturedImage::class);
    }

    // /**
    //  * @return FeaturedImage[] Returns an array of FeaturedImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FeaturedImage
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
