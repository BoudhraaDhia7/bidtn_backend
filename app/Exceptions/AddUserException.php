<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Exception;

class AddUserException extends Exception
{
   
    public function __construct($message = 'user_registration_failed', $code = 422, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        Log::debug($this->getMessage());
    }
       
}
