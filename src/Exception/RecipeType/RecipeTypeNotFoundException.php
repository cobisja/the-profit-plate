<?php

declare(strict_types=1);

namespace App\Exception\RecipeType;

use Exception;

class RecipeTypeNotFoundException extends Exception
{
    protected $message = 'Recipe Type not found';
}