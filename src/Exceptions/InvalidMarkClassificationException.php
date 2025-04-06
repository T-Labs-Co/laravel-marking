<?php

namespace TLabsCo\LaravelMarking\Exceptions;

use Throwable;

class InvalidMarkClassificationException extends \Exception
{
    protected $message = 'Invalid Mark Classification';

    public function __construct($classification, $message = '', $code = 0, ?Throwable $previous = null)
    {
        $message = "Invalid Mark Classification {$classification}";

        parent::__construct($message, $code, $previous);
    }
}
