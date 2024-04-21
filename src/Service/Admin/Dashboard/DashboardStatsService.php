<?php

declare(strict_types=1);

namespace App\Service\Admin\Dashboard;

use App\Repository\ProductRepository;
use App\Repository\ProductTypeRepository;
use App\Repository\RecipeRepository;
use App\Repository\RecipeTypeRepository;

readonly class DashboardStatsService
{
    public function __construct(
        private RecipeRepository $recipeRepository,
        private RecipeTypeRepository $recipeTypeRepository,
        private ProductRepository $productRepository,
        private ProductTypeRepository $productTypeRepository,
    ) {
    }

    public function execute(): array
    {
        $totalRecipeTypes = $this->recipeTypeRepository->getTotal();
        $recipeTypesWithTotalRecipes = $this->recipeTypeRepository->numberOfRecipesPerType();
        $totalRecipes = $this->recipeRepository->getTotal();
        $latestRecipes = $this->recipeRepository->latestUpdated();
        $recipeUpdatesVariation = $this->recipeRepository->calculateUpdatesVariationForThisMonth();

        $totalProductTypes = $this->productTypeRepository->getTotal();
        $productTypesWithTotalProducts = $this->productTypeRepository->numberOfProductsPerType();
        $totalProducts = $this->productRepository->getTotal();
        $latestProducts = $this->productRepository->latestUpdated();
        $productUpdatesVariation = $this->productRepository->calculateUpdatesVariationForThisMonth();

        return [
            'overview' => [
                'recipe_types' => [
                    'total' => $totalRecipeTypes,
                    'total_recipes_per_type' => $recipeTypesWithTotalRecipes,

                ],
                'recipes' => [
                    'total' => $totalRecipes,
                    'updates_variation' => $recipeUpdatesVariation
                ],
                'product_types' => [
                    'total' => $totalProductTypes,
                    'total_products_per_type' => $productTypesWithTotalProducts,
                ],
                'products' => [
                    'total' => $totalProducts,
                    'updates_variation' => $productUpdatesVariation
                ],
            ],
            'latest_updated' => [
                'recipes' => $latestRecipes,
                'products' => $latestProducts,
            ]
        ];
    }
}