<?php

declare(strict_types=1);

namespace App\Service\Admin\RecipeIngredient;

use App\Entity\RecipeIngredient;
use App\Repository\ConversionFactorRepository;

readonly class IngredientCostCalculatorService
{
    public function __construct(private ConversionFactorRepository $conversionFactorRepository)
    {
    }

    public function execute(RecipeIngredient $ingredient): float
    {
        $product = $ingredient->getProduct();
        $productUnit = $product->getUnit();
        $productPricePerUnit = (float)$product->getPricePerUnit();
        $ingredientUnit = $ingredient->getUnit();
        $ingredientQuantity = $ingredient->getQuantity();

        if ($ingredientUnit === $productUnit) {
            return $ingredientQuantity * $productPricePerUnit;
        }

        if (!$conversionFactor = $this->conversionFactorRepository->conversionFactorForUnits(
            $productUnit,
            $ingredientUnit
        )) {
            return 0.0;
        }

        return $productPricePerUnit * $ingredientQuantity / $conversionFactor;
    }
}