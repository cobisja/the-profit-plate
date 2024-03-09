<?php

declare(strict_types=1);

namespace App\Exception\ProductType;

use Exception;

class ProductTypeNotFoundException extends Exception
{
    protected $message = 'Product Type not found';
}