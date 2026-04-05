<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Enum\OrderStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function getMonthlySalesData(\DateTimeInterface $date): array
    {
        ['start' => $startOfMonth, 'end' => $endOfMonth] = $this->getMonthRange($date);

        // On sélectionne juste les colonnes nécessaires, pas l'objet entier
        $results = $this->createQueryBuilder('o')
            ->select('o.createdAt, o.total')
            ->where('o.createdAt BETWEEN :start AND :end')
            ->andWhere('o.status IN (:statuses)')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->setParameter('statuses', [OrderStatus::PAID, OrderStatus::SHIPPED])
            ->getQuery()
            ->getScalarResult(); // On récupère un tableau de données brutes

        $chartData = array_fill(1, 31, 0);

        foreach ($results as $row) {
            $day = (int) (new \DateTime($row['createdAt']))->format('j');
            $chartData[$day] += $row['total'] / 100;
        }

        return $chartData;
    }

    public function getFiveBestClients(\DateTimeInterface $date): array
    {
        ['start' => $startOfMonth, 'end' => $endOfMonth] = $this->getMonthRange($date);

        return $this->createQueryBuilder('o')
            // On sélectionne l'email (unique) ou on concatène nom/prénom
            ->select('u.email AS clientEmail, SUM(o.total) / 100 AS totalSpent')
            ->innerJoin('o.client', 'u')
            ->where('o.status IN (:statuses)')
            ->andWhere('o.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->setParameter('statuses', [OrderStatus::PAID, OrderStatus::SHIPPED])
            // On ajoute l'email au groupement pour respecter la norme SQL
            ->groupBy('u.id, u.email')
            ->orderBy('totalSpent', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getScalarResult(); // Retourne un tableau associatif simple
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

    private function getMonthRange(\DateTimeInterface $date): array
    {
        return [
            'start' => (clone $date)->modify('first day of this month')->setTime(0, 0, 0),
            'end' => (clone $date)->modify('last day of this month')->setTime(23, 59, 59),
        ];
    }
}
