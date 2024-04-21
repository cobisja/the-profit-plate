<?php

namespace App\Repository;

use App\Entity\ProductType;

/**
 * @method ProductType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductType[]    findAll()
 * @method ProductType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductTypeRepository extends BaseRepository
{
    protected static string $entityClassName = ProductType::class;

    public function getTotal(): int
    {
        return $this->createQueryBuilder('pt')
            ->select('COUNT(pt)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function numberOfProductsPerType(): array
    {
        return $this->createQueryBuilder('pt')
            ->select('pt as product_type, COUNT(p) AS number_of_products')
            ->innerJoin('pt.products', 'p')
            ->groupBy('pt')
            ->getQuery()
            ->getArrayResult();
    }
}
