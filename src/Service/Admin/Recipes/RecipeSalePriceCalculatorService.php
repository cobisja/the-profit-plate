<?php

declare(strict_types=1);

namespace App\Service\Admin\Recipes;

use App\Exception\Recipe\RecipeNotFoundException;
use App\Repository\RecipeRepository;

readonly class RecipeSalePriceCalculatorService
{
    public function __construct(private RecipeRepository $recipeRepository)
    {
    }

    /**
     * @throws RecipeNotFoundException
     */
    public function execute(
        string $recipeId,
        $numberOfServings,
        $expensesPercentage,
        $profitPercentage,
        $ingredientCosts
    ): float {
        if (!$recipe = $this->recipeRepository->findByIdWithTypeAndIngredients($recipeId)) {
            throw new RecipeNotFoundException();
        }

        $ingredientsCost = $ingredientCosts ?? $recipe->getIngredientCosts();
        $recipeProfit = $ingredientsCost * ($profitPercentage ?? $recipe->getProfitPercentage()) / 100;
        $expenses = $recipeProfit + $recipeProfit * ($expensesPercentage ?? $recipe->getExpensesPercentage()) / 100;
        $numberOfServings = $numberOfServings ?? $recipe->getNumberOfServings();

        return round(($ingredientsCost + $recipeProfit + $expenses) / $numberOfServings, 2);
    }
}
