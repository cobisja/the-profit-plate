<?php

declare(strict_types=1);

namespace App\Exception\ConversionFactor;

use Exception;

class ConversionFactorNotFoundException extends Exception
{
    protected $message = 'Conversion factor not found';
}