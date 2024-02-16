<?php

namespace App\Repository;

use App\Entity\ConversionFactor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConversionFactor>
 *
 * @method ConversionFactor|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConversionFactor|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConversionFactor[]    findAll()
 * @method ConversionFactor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversionFactorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConversionFactor::class);
    }

//    /**
//     * @return ConversionFactor[] Returns an array of ConversionFactor objects
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

//    public function findOneBySomeField($value): ?ConversionFactor
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
