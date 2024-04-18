<?php

declare(strict_types=1);

namespace App\Exception\RecipeIngredient;

use Exception;

class RecipeIngredientNotFoundException extends Exception
{
    protected $message = 'Recipe ingredient not found';
}