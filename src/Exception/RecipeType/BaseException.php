<?php

declare(strict_types=1);

namespace App\Exception\RecipeType;

use Exception;
use Throwable;

class BaseException extends Exception
{
    protected $message = "";

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}