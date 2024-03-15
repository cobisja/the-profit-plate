<?php

namespace App\Repository;

use App\Entity\ConversionFactor;

/**
 * @method ConversionFactor|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConversionFactor|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConversionFactor[]    findAll()
 * @method ConversionFactor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversionFactorRepository extends BaseRepository
{
    protected static string $entityClassName = ConversionFactor::class;
}
