<?php

declare(strict_types=1);

namespace App\Twig\Extension\Recipes;

use App\Entity\Recipe;
use App\Service\Admin\Recipes\RecipeSalePriceCalculatorService;
use Twig\Extension\RuntimeExtensionInterface;

readonly class RecipeSalePriceRuntime implements RuntimeExtensionInterface
{
    public function __construct(private RecipeSalePriceCalculatorService $recipeSalePriceCalculatorService)
    {
    }

    public function calculateSalePrice(Recipe $recipe): float
    {
        return $this->recipeSalePriceCalculatorService->execute($recipe);
    }
}
