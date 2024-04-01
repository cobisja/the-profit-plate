<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Common\Collections\Criteria;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends BaseRepository
{
    protected static string $entityClassName = Recipe::class;

    public function getAllWithTypeAndIngredients(): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('rt, i')
            ->innerJoin('r.recipeType', 'rt')
            ->leftJoin('r.ingredients', 'i')
            ->getQuery()
            ->getResult();
    }

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
}
