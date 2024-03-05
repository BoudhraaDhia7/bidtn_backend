<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Exception;

class AuthException extends Exception
{
   
    public function __construct($message='token_invalid', $code = 401, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        Log::debug($this->getMessage());
    }
       
}
