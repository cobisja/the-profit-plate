<?php

declare(strict_types=1);

namespace App\Twig\Extension\Recipes;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RecipeSalePriceFunction extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'sale_price_of_recipe',
                [RecipeSalePriceRuntime::class, 'calculateSalePrice']
            ),
        ];
    }
}
