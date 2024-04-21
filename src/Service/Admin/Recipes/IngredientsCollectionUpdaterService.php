<?php

declare(strict_types=1);

namespace App\Service\Admin\Recipes;

use App\Entity\RecipeIngredient;
use App\Exception\Product\ProductNotFoundException;
use App\Service\Admin\RecipeIngredient\IngredientCostCalculatorService;
use Doctrine\ORM\EntityManagerInterface;

final readonly class IngredientsCollectionUpdaterService
{
    public function __construct(
        private IngredientCostCalculatorService $ingredientCostCalculatorService,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param RecipeIngredient[] $ingredients
     */
    public function execute(array $ingredients): void
    {
        array_walk($ingredients, function (&$ingredient) {
            /** @var RecipeIngredient $ingredient */
            try {
                $ingredient->setCost(
                    (string)$this->ingredientCostCalculatorService->execute(
                        (string)$ingredient->getProduct()->getId(),
                        (float)$ingredient->getQuantity(),
                        $ingredient->getUnit()
                    )
                );

                $this->entityManager->persist($ingredient);
            } catch (ProductNotFoundException) {
                $ingredient->setCost(null);
            }
        });
    }
}