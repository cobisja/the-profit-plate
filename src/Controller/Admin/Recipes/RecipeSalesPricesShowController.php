<?php

declare(strict_types=1);

namespace App\Controller\Admin\Recipes;

use App\Exception\Recipe\RecipeNotFoundException;
use App\Service\Admin\Recipes\RecipeSalePriceCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeSalesPricesShowController extends AbstractController
{
    public function __construct(private readonly RecipeSalePriceCalculatorService $recipeSalePriceCalculatorService)
    {
    }

    #[Route(
        path: '/recipe/{id}/sale_price',
        name: 'admin_recipe_sales_prices_show',
        methods: ["GET"]
    )]
    public function __invoke(string $id, Request $request): JsonResponse
    {
        try {
            $recipeSalePrice = $this->recipeSalePriceCalculatorService->execute(
                $id,
                $request->query->get('number_of_servings'),
                $request->query->get('expenses_percentage'),
                $request->query->get('profit_percentage'),
                $request->query->get('ingredient_costs'),
            );

            $response = $this->json(['data' => ['sale_price' => $recipeSalePrice]]);
        } catch (RecipeNotFoundException $exception) {
            $response = $this->json(
                ['error' => ['message' => $exception->getMessage()]],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $response;
    }
}