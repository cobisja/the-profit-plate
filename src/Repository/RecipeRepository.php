<?php

namespace App\Repository;

use App\Entity\Recipe;
use DateTimeImmutable;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Uid\Uuid;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends BaseRepository
{
    protected static string $entityClassName = Recipe::class;

    /**
     * @return Recipe[]
     */
    public function getAllWithTypeAndIngredients(): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('rt, i')
            ->innerJoin('r.recipeType', 'rt')
            ->leftJoin('r.ingredients', 'i')
            ->getQuery()
            ->getResult();
    }

    public function findByIdWithTypeAndIngredients(string $id): ?Recipe
    {
        try {
            $recipe = $this->createQueryBuilder('r')
                ->addSelect('rt, i, p')
                ->innerJoin('r.recipeType', 'rt')
                ->leftJoin('r.ingredients', 'i')
                ->leftJoin('i.product', 'p')
                ->where('r.id = :id')
                ->setParameter('id', Uuid::fromString($id)->toBinary(), ParameterType::BINARY)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException) {
            $recipe = null;
        }

        return $recipe;
    }

    /**
     * @return Recipe[]
     */
    public function searchByNameAndRecipeTypeName(string $name = '', ?string $recipeTypeName = ''): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->addSelect('rt, i')
            ->innerJoin('r.recipeType', 'rt')
            ->leftJoin('r.ingredients', 'i');

        if ($name) {
            $queryBuilder
                ->where($queryBuilder->expr()->like('r.name', ':name'))
                ->setParameter('name', '%' . $name . '%');
        }

        if ($recipeTypeName) {
            $queryBuilder
                ->andWhere('rt.name = :recipe_type_name')
                ->setParameter('recipe_type_name', $recipeTypeName);
        }

        return $queryBuilder
            ->orderBy('r.name', Criteria::ASC)
            ->getQuery()
            ->getResult();
    }

    public function getTotal(): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Recipe[]
     */
    public function latestUpdated(int $limit = 5): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('rt')
            ->innerJoin('r.recipeType', 'rt')
            ->setMaxResults($limit)
            ->orderBy('r.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function calculateUpdatesVariationForThisMonth(): float
    {
        $firstDateOfThisMonth = new DateTimeImmutable('first day of this month');
        $lastDateOfThisMonth = new DateTimeImmutable('last day of this month');

        $queryBuilder = $this->createQueryBuilder('r');

        $recipesThisMonth = $queryBuilder
            ->select('COUNT(r)')
            ->where(
                $queryBuilder->expr()->between('r.updatedAt', ':first_date', ':last_date')
            )
            ->setParameter('first_date', $firstDateOfThisMonth->format('Y-m-d'))
            ->setParameter('last_date', $lastDateOfThisMonth->format('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult();

        $firstDateOfLastMonth = new DateTimeImmutable('first day of last month');
        $lastDateOfLastMonth = new DateTimeImmutable('last day of last month');

        $recipesLastMonth = $queryBuilder
            ->select('COUNT(r)')
            ->where(
                $queryBuilder->expr()->between('r.updatedAt', ':first_date', ':last_date')
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
