<?php

namespace App\Repository;

use App\Entity\ProductPriceVariation;

/**
 * @method ProductPriceVariation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductPriceVariation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductPriceVariation[]    findAll()
 * @method ProductPriceVariation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductPriceVariationRepository extends BaseRepository
{
    protected static string $entityClassName = ProductPriceVariation::class;
}
