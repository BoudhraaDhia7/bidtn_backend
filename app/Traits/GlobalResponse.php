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
    public static function GlobalResponse(string $message, int $status = 500, $data = null, bool $isPaginated = false)
    {
        if ($data) {
            if ($isPaginated) {
                return response()->json(
                    [
                        'message' => __('messages.' . $message),
                        'data' => $data->items(),
                        'meta' => [
                            'current_page' => $data->currentPage(),
                            'per_page' => $data->perPage(),
                            'total' => $data->total(),
                        ],
                    ],
                    $status,
                );
            } else {
                return response()->json(
                    [
                        'message' => __('messages.' . $message),
                        'data' => $data,
                    ],
                    $status,
                );
            }
        }

        return response()->json(
            [
                'message' => __('messages.' . $message),
            ],
            $status,
        );
    }
}
