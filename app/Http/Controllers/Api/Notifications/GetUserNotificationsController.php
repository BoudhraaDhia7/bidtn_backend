<?php

namespace App\Http\Controllers\Api\Notifications;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Repositories\NotificationRepository;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class GetUserNotificationsController extends Controller
{
    use GlobalResponse;

    public function __invoke(): JsonResponse
    {
        $user = auth()->user();
        try {
            $notifications = NotificationRepository::getUserNotifications($user);
            return $this->GlobalResponse('notifications_retrieved', Response::HTTP_OK, $notifications);
        } catch (\Exception $e) {
            Log::error('GetUserNotificationsController: Error retrieving ' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode(500));
        }
    }
}