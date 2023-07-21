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

    public static function notifyClientAcquired($client)
    {
        try {
            $payload = [
                'title' => 'New Client Acquired',
                'body' => 'A new client ' . $client->name . ' has been acquired',
                'type' => 'client_acquired',
                'metadata' => [],
            ];

            self::sendNotify($payload);
        } catch (Throwable $th) {
            logger('Error sending notification for client acquired: ' . $th->getMessage());
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

    public function notifyOverdueLease($count)
    {
        try {
            $payload = [
                'title' => 'Overdue Lease',
                'body' => 'There are ' . $count . ' equipment that have overdue leases',
                'type' => 'overdue_lease',
                'metadata' => [],
            ];

            $this->sendNotify($payload);
        } catch (Throwable $th) {
            logger('Error sending notification for items sold: ' . $th->getMessage());
        }
    }

    public function notifyItemSold($invoice)
    {
        $isLease = !is_null($invoice->lease);

        try {
            $payload = [
                'title' => $isLease ? 'Items Leased' : 'Items Sold',
                'body' => 'New Items ' . ($isLease ? 'leased' : 'sold') . ' to ' . $invoice->client->name,
                'type' => $isLease ? 'items_leased' : 'items_sold',
                'metadata' => [],
            ];

            $this->sendNotify($payload);
        } catch (Throwable $th) {
            logger('Error sending notification for items sold: ' . $th->getMessage());
        }
    }

    // LowStockLevel, OverdueLease
}
