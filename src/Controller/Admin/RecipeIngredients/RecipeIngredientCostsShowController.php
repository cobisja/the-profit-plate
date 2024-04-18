<?php

declare(strict_types=1);

namespace App\Controller\Admin\RecipeIngredients;

use App\Exception\Product\ProductNotFoundException;
use App\Service\Admin\RecipeIngredient\IngredientCostCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class RecipeIngredientCostsShowController extends AbstractController
{
    public function __construct(private readonly IngredientCostCalculatorService $ingredientCostCalculatorService)
    {
    }

    #[Route(
        path: '/recipe_ingredients/costs',
        name: 'admin_recipe_ingredient_costs_show',
        methods: ["POST"]
    )]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $recipeIngredientCost = $this->ingredientCostCalculatorService->execute(
                productId: $request->get('product_id'),
                quantity: (float)$request->get('quantity', 0),
                unit: $request->get('unit'),
            );

            $response = $this->json(['data' => ['cost' => $recipeIngredientCost]]);
        } catch (ProductNotFoundException $exception) {
            $response = $this->json(
                ['error' => ['message' => $exception->getMessage()]],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $response;
    }
}