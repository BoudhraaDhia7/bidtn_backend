<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Exception;

class GlobalException extends Exception
{
   
    public function __construct($message, $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        Log::debug($this->getMessage());
    }
       
}
