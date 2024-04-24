<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Exception;
use GuzzleHttp\Psr7\Response;

class GlobalException extends Exception
{
   
    public function __construct($message="internal_server_error", $code = 500 ,Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        Log::debug($this->getMessage());
    }

}
