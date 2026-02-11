<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }



    public function getcommentsbyarticle(Product $product): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.product = :product')
            ->andWhere('c.isValid = true')
            ->setParameter('product', $product)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function hasAlreadyCommented(User $user, Product $product): bool
{
    return (bool) $this->createQueryBuilder('c')
        ->select('COUNT(c.id)')
        ->where('c.author = :user')
        ->andWhere('c.product = :product')
        ->setParameter('user', $user)
        ->setParameter('product', $product)
        ->getQuery()
        ->getSingleScalarResult();
}

//    /**
//     * @return Comment[] Returns an array of Comment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Comment
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
