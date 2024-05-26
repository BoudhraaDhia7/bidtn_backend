<?php

namespace App\Http\Controllers\Api\Statistics;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Repositories\StatisticsRepository;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserStatisticsController extends Controller
{
    use GlobalResponse;
    
    public function __invoke(): JsonResponse
    {
        $user = auth()->user();
        try {
            $response = StatisticsRepository::userStats($user);
            return $this->GlobalResponse('statistics_retrieved', Response::HTTP_OK, [$response]);
        } catch (\Exception $e) {
            Log::error('UserStatisticsController: Error retrieving ' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode(500));
        }
    }
}
