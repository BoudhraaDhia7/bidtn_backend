<?php

namespace App\Traits;

trait SuccessResponse
{
    /**
     * @param string $message
     * @param int $status
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function SuccessResponse(string $message,int $status, $data = null)
    {
        if ($data){
            return response()->json([
                'message' => __($message),
                'data' => $data
            ], $status);
        }

        return response()->json([
            'message' => __($message),
        ], $status);

    }
}