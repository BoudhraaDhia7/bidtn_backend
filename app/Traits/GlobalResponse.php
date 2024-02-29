<?php

namespace App\Traits;

trait GlobalResponse
{
    /**
     * @param string $message
     * @param int $status
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function GlobalResponse(string $message,int $status, $data = null)
    {
        if ($data){
            return response()->json([
                'message' => $message,
                'data' => $data
            ], $status);
        }

        return response()->json([
            'message' => $message
        ], $status);

    }
}