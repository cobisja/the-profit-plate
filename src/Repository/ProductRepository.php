<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Recipe;
use DateTimeImmutable;
use Doctrine\DBAL\ParameterType;
use Symfony\Component\Uid\Uuid;

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

    public function findByIdWithItsRelationships(string $id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->addSelect('pt, pv, ri, r')
            ->innerJoin('p.productType', 'pt')
            ->leftJoin('p.priceVariations', 'pv')
            ->leftJoin('p.recipeIngredients', 'ri')
            ->leftJoin('ri.recipe', 'r')
            ->where('p.id = :id')
            ->setParameter('id', Uuid::fromString($id)->toBinary(), ParameterType::BINARY)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getTotal(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Recipe[]
     */
    public function latestUpdated(int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->addSelect('pt')
            ->innerJoin('p.productType', 'pt')
            ->setMaxResults($limit)
            ->orderBy('p.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function calculateUpdatesVariationForThisMonth(): float
    {
        $firstDateOfThisMonth = new DateTimeImmutable('first day of this month');
        $lastDateOfThisMonth = new DateTimeImmutable('last day of this month');

        $queryBuilder = $this->createQueryBuilder('p');

        $recipesThisMonth = $queryBuilder
            ->select('COUNT(p)')
            ->where(
                $queryBuilder->expr()->between('p.updatedAt', ':first_date', ':last_date')
            )
            ->setParameter('first_date', $firstDateOfThisMonth->format('Y-m-d'))
            ->setParameter('last_date', $lastDateOfThisMonth->format('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult();

        $firstDateOfLastMonth = new DateTimeImmutable('first day of last month');
        $lastDateOfLastMonth = new DateTimeImmutable('last day of last month');

        $recipesLastMonth = $queryBuilder
            ->select('COUNT(p)')
            ->where(
                $queryBuilder->expr()->between('p.updatedAt', ':first_date', ':last_date')
            )
            ->setParameter('first_date', $firstDateOfLastMonth->format('Y-m-d'))
            ->setParameter('last_date', $lastDateOfLastMonth->format('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult();

        if ($recipesThisMonth === $recipesLastMonth) {
            return 0;
        }

        if (!$recipesThisMonth) {
            return -1;
        }

        if (!$recipesLastMonth) {
            return 1;
        }

        return (float)($recipesThisMonth - $recipesLastMonth) / $recipesLastMonth;
    }
}
