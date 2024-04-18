<?php

declare(strict_types=1);

namespace App\Service\Admin\Recipes;

use App\Entity\Recipe;
use App\Exception\Recipe\RecipeNotFoundException;
use App\Repository\RecipeRepository;

readonly class RecipeShowService
{
    public function __construct(private RecipeRepository $recipeRepository)
    {
    }

    /**
     * @throws RecipeNotFoundException
     */
    public function execute(string $id): Recipe
    {
        if (!$recipe = $this->recipeRepository->findByIdWithTypeAndIngredients($id)) {
            throw new RecipeNotFoundException();
        }

        return $recipe;
    }
}