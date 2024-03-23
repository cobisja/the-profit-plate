<?php

declare(strict_types=1);

namespace App\Exception\Shared;

use Exception;

class PictureNotUploadedException extends Exception
{
    protected $message = 'The product picture could not be uploaded';
}