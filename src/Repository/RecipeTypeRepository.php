<?php

namespace App\Repository;

use App\Entity\RecipeType;

/**
 * @method RecipeType|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeType|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeType[]    findAll()
 * @method RecipeType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeTypeRepository extends BaseRepository
{
    protected static string $entityClassName = RecipeType::class;
}
