<?php

declare(strict_types=1);

namespace App\Exception\Shared;

use Exception;

class InvalidPictureException extends Exception
{
    protected $message = 'Invalid product picture';
}