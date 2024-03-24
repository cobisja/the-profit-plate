<?php

declare(strict_types=1);

namespace App\Exception\Shared;

use Exception;

class PictureCannotBeReplacedException extends Exception
{
    protected $message = 'The requested picture file cannot be replaced';
}