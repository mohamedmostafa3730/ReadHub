<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    protected int $statusCode;
    public function __construct(string $message = "Book error", int $statusCode = 500)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }
}