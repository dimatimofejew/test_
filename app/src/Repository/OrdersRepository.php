<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Orders>
 *
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    /**
     * Группировка заказов по месяцам, годам или дням.
     */
    public function groupByDate(string $groupBy, int $page, int $limit): array
    {
        // Определяем поле группировки
        switch ($groupBy) {
            case 'year':
                $dateFormat = 'DATE_FORMAT(o.createDate,\'%Y\') ';
                break;
            case 'day':
                $dateFormat = 'DATE(o.createDate)';
                break;
            case 'month':
            default:
                $dateFormat = 'DATE_FORMAT(o.createDate, \'%Y-%m\')';
                break;
        }

        $qb =   $this->createQueryBuilder('o')
            ->select("$dateFormat as groupDate, count(o.name) total")
            ->groupBy('groupDate')
            ->getQuery()
            ->getResult();
        $totalCount = count($qb);
        // Подсчитываем количество страниц
        $totalPages = (int) ceil($totalCount / $limit);
        // Выполняем сам запрос на группировку
        $qb = $this->createQueryBuilder('o')
            ->select("$dateFormat as groupDate, COUNT(o.id) as orderCount")
            ->groupBy('groupDate')
            ->orderBy('groupDate', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        $result = $qb->getQuery()->getResult();
        // Возвращаем результат и количество страниц
        return [
            'results' => $result,
            'totalPages' => $totalPages,
        ];
    }
//    /**
//     * @return Orders[] Returns an array of Orders objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Orders
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
