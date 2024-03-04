<?php

namespace App\Traits;

trait ErrorResponse
{
    /**
     * @param string $message
     * @param int $status
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function ErrorResponse(string $message,int $status, $data = null)
    {
        return response()->json([
            'message' => __('messages.'.$message)
        ], $status);

    }
}