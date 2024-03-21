<?php

declare(strict_types=1);

namespace App\Exception\Product;

use Exception;

class ProductNotFoundException extends Exception
{
    protected $message = 'Product not found';
}