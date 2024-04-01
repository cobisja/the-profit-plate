<?php

declare(strict_types=1);

namespace App\Service\Admin\Recipes;

use App\Entity\Product;
use App\Repository\RecipeRepository;

readonly class RecipeIndexService
{
    public function __construct(private RecipeRepository $recipeRepository)
    {
    }

    /**
     * @return Product[]
     */
    public function execute(): array
    {
        return $this->recipeRepository->getAllWithTypeAndIngredients();
    }
}