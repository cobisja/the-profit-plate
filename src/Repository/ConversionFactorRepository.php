<?php

namespace App\Repository;

use App\Entity\ConversionFactor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Parameter;

/**
 * @method ConversionFactor|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConversionFactor|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConversionFactor[]    findAll()
 * @method ConversionFactor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversionFactorRepository extends BaseRepository
{
    protected static string $entityClassName = ConversionFactor::class;

    public function conversionFactorForUnits(string $originUnit, string $targetUnit): ?float
    {
        $originUnitParam = new Parameter('origin_unit', $originUnit);
        $targetUnitParam = new Parameter('target_unit', $targetUnit);

        try {
            $result = $this->createQueryBuilder('cf')
                ->select('cf.factor')
                ->where('cf.originUnit = :origin_unit')
                ->andWhere('cf.targetUnit = :target_unit')
                ->setParameters(new ArrayCollection([$originUnitParam, $targetUnitParam]))
                ->getQuery()
                ->getSingleScalarResult();

            return (float)$result;
        } catch (NoResultException) {
            try {
                $result = $this->createQueryBuilder('cf')
                    ->select('cf.factor')
                    ->where('cf.originUnit = :target_unit')
                    ->andWhere('cf.targetUnit = :origin_unit')
                    ->setParameters(new ArrayCollection([$targetUnitParam, $originUnitParam]))
                    ->getQuery()
                    ->getSingleScalarResult();

                return (1 / (float)$result);
            } catch (NoResultException) {
                return null;
            }
        }
    }
}
