<?php
namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository
{
    public static function saveNotification($data)
    {
        Notification::create($data);
    }

    public static function getUserNotifications($user)
    {
        return Notification::where('user_id', $user->id)->get();
    }
}
