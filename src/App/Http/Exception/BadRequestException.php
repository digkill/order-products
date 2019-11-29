<?php

namespace App\Http\Exception;

class BadRequestException extends \RuntimeException
{

    public function __construct(string $message = '', int $code = 400, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}