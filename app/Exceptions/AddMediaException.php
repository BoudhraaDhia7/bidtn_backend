<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Exception;

class AddMediaException extends Exception
{
   
    public function __construct($message='media_creation_failed', $code = 422, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        Log::debug($this->getMessage());
    }
       
}
