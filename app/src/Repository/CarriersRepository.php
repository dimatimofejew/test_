<?php

namespace App\Repository;

use App\Entity\Carriers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carriers>
 *
 * @method Carriers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carriers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carriers[]    findAll()
 * @method Carriers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarriersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carriers::class);
    }

//    /**
//     * @return Carriers[] Returns an array of Carriers objects
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

//    public function findOneBySomeField($value): ?Carriers
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
