<?php

namespace App\Traits;

use App\Events\SendAppNotification;
use Throwable;

trait Broadcastable
{
    public static function notifyUserCreated($user)
    {
        try {
            $payload = [
                'id' => $user->id,
                'title' => 'New User Created',
                'body' => 'A new user ' . $user->name . ' has been created',
                'type' => 'user_created',
                'metadata' => [],
            ];

            self::sendNotify($payload);
        } catch (Throwable $th) {
            logger('Error sending notification for user created: ' . $th->getMessage());
        }
    }

    public static function sendNotify($data, $destination = null)
    {
        $data = (object) $data;

        $notification = [
            'title' => $data->title ?? null,
            'body' => $data->body,
            'type' => $data->type ?? 'general',
            'date' => now(),
            'metadata' => $data->metadata ?? [],
        ];

        event(new SendAppNotification($notification, $destination ?? $data->id));
    }

    public function notifyLowStock($count)
    {
        try {
            $payload = [
                'title' => 'Low Stock',
                'body' => 'There are ' . $count . ' equipment running low on stock',
                'type' => 'low_stock',
                'metadata' => [],
            ];

            $this->sendNotify($payload);
        } catch (Throwable $th) {
            logger('Error sending notification for items sold: ' . $th->getMessage());
        }
    }
}
