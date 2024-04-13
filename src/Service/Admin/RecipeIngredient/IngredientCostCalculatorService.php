<?php

declare(strict_types=1);

namespace App\Service\Admin\RecipeIngredient;

use App\Exception\Product\ProductNotFoundException;
use App\Repository\ConversionFactorRepository;
use App\Repository\ProductRepository;

readonly class IngredientCostCalculatorService
{
    public function __construct(
        private ProductRepository $productRepository,
        private ConversionFactorRepository $conversionFactorRepository
    ) {
    }

    /**
     * @throws ProductNotFoundException
     */
    public function execute(string $productId, float $quantity, string $unit): float
    {
        if (!$product = $this->productRepository->find($productId)) {
            throw new ProductNotFoundException();
        }

        $productUnit = $product->getUnit();
        $productPricePerUnit = $product->netCost();

        if ($unit === $productUnit) {
            return $quantity * $productPricePerUnit;
        }

        if (!$conversionFactor = $this->conversionFactorRepository->conversionFactorForUnits($productUnit, $unit)) {
            return 0.0;
        }

        return $productPricePerUnit * $quantity / $conversionFactor;
    }
}