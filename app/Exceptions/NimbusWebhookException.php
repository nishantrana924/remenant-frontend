<?php

namespace App\Exceptions;

use Exception;

class NimbusWebhookException extends Exception
{
    public $context;

    public function __construct(string $message = "", int $code = 400, array $context = [], \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }
}
