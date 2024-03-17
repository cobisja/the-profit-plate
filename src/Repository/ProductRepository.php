<?php

namespace App\Repository;

use App\Entity\Product;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends BaseRepository
{
    protected static string $entityClassName = Product::class;

    /**
     * @return Product[]
     */
    public function getAllWithType(): array
    {
        return $this->createQueryBuilder('p')
            ->addSelect('pt')
            ->innerJoin('p.productType', 'pt')
            ->getQuery()
            ->getResult();
    }
}
