<?php

declare(strict_types=1);

namespace App\Service\Admin\Recipes;

use App\Entity\Recipe;

readonly class RecipeSalePriceCalculatorService
{
    public function execute(Recipe $recipe): float
    {
        $ingredientsCost = $recipe->getIngredientCosts();
        $recipeProfit = $ingredientsCost * ($recipe->getProfitPercentage()) / 100;
        $expenses = $recipeProfit + $recipeProfit * ($recipe->getExpensesPercentage()) / 100;
        $numberOfServings = $recipe->getNumberOfServings();

        return round(($ingredientsCost + $recipeProfit + $expenses) / $numberOfServings, 2);
    }
}
