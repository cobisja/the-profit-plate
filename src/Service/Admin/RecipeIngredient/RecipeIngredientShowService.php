<?php

declare(strict_types=1);

namespace App\Service\Admin\RecipeIngredient;

use App\Entity\RecipeIngredient;
use App\Exception\RecipeIngredient\RecipeIngredientNotFoundException;
use App\Repository\RecipeIngredientRepository;

readonly class RecipeIngredientShowService
{
    public function __construct(private RecipeIngredientRepository $recipeIngredientRepository)
    {
    }

    /**
     * @throws RecipeIngredientNotFoundException
     */
    public function execute(string $id): RecipeIngredient
    {
        if (!$recipeIngredient = $this->recipeIngredientRepository->find($id)) {
            throw new RecipeIngredientNotFoundException();
        }

        return $recipeIngredient;
    }
}