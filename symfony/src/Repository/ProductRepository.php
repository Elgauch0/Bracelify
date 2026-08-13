<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }




    public function findAvailableProducts(): array
    {
        return $this->createQueryBuilder('p')
           ->andWhere('p.Quantity > 0')
           ->getQuery()
           ->getResult();

    }
    public function findAvailableProductsForCategory(int $categoryId):array
    {
        return $this->createQueryBuilder('p')
          ->andWhere('p.Quantity > 0')
          ->andWhere('p.category = :categoryId')
          ->setParameter('categoryId', $categoryId)
          ->getQuery()
          ->getResult();
    }

   public function findAvailableProductsWithFilters(?int $categoryId = null, ?int $collectionId = null): array
{
    $qb = $this->createQueryBuilder('p')
        ->andWhere('p.Quantity > 0');

    if ($categoryId) {
        $qb->andWhere('p.category = :categoryId')
           ->setParameter('categoryId', $categoryId);
    }

    if ($collectionId) {
        $qb->innerJoin('p.productCollections', 'col')
           ->andWhere('col.id = :collectionId')
           ->setParameter('collectionId', $collectionId);
    }

    return $qb->getQuery()->getResult();
}



    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
