<?php

declare(strict_types=1);

namespace App\Exception\Recipe;

use Exception;

class RecipeNotFoundException extends Exception
{
    protected $message = 'Recipe not found';
}