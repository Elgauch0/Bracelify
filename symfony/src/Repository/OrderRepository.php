<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Entity\User;
use App\Enum\OrderStatus;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }




    // ...
public function hasPurchasedProduct(User $user, Product $product): bool
{
    return (bool) $this->createQueryBuilder('o')
        ->select('COUNT(o.id)')
        ->innerJoin('o.items', 'i') 
        ->where('o.client = :user')
        ->andWhere('i.product = :product')
        ->andWhere('o.Status = :status')
        ->setParameter('user', $user)
        ->setParameter('product', $product)
        ->setParameter('status', OrderStatus::PAID)
        ->getQuery()
        ->getSingleScalarResult();
}



    //    /**
    //     * @return Order[] Returns an array of Order objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
