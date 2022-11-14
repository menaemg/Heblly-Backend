<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\GiftNotification;
use App\Http\Resources\NotificationResource;
use App\Notifications\GratitudeNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $notifications = $user->notifications()->whereNotIn('type', [GiftNotification::class , GratitudeNotification::class])->get();

        $notifications = NotificationResource::collection($notifications);

        return response()->json([
            'status' => true,
            'message' => 'Notifications List',
            'data' => $notifications,
            'meta' => [
                'notifications_count' => $notifications->count(),
                'unread_notifications_count' => $user->unreadNotifications()->whereNotIn('type', [GiftNotification::class , GratitudeNotification::class])->count()
            ],
        ]);

        return jsonResponse(true, "Notifications List", $notifications);
    }

    public function indexGift()
    {
        $user = auth()->user();

        $notifications = $user->notifications()->whereIn('type', [GiftNotification::class , GratitudeNotification::class])->get();

        $notifications = NotificationResource::collection($notifications);

        return response()->json([
            'status' => true,
            'message' => 'Notifications List',
            'data' => $notifications,
            'meta' => [
                'notifications_count' => $notifications->count(),
                'unread_notifications_count' => $user->unreadNotifications()->whereIn('type', [GiftNotification::class , GratitudeNotification::class])->count()
            ],
        ]);

        return jsonResponse(true, "Notifications List", $notifications);
    }

    public function read($id)
    {
        $user = auth()->user();

        $notification = $user->notifications()->where('id', $id)->first();

        if (!$notification) {
            return jsonResponse(false, "Notification Not Found", null, 404);
        }

        $notification->markAsRead();

        return jsonResponse(true, "Notification Marked As Read");
    }

    public function readAll()
    {
        $user = auth()->user();

        $unreadNotifications = $user->unreadNotifications()->whereNotIn('type', [GiftNotification::class , GratitudeNotification::class])->get();

        foreach ($unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        return jsonResponse(true, "All Notifications Marked As Read");
    }

    public function readAllGift()
    {
        $user = auth()->user();

        $unreadNotifications = $user->unreadNotifications()->whereIn('type', [GiftNotification::class , GratitudeNotification::class])->get();

        foreach ($unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        return jsonResponse(true, "All Notifications Marked As Read");
    }

    public function unRead($id)
    {
        $user = auth()->user();

        $notification = $user->notifications()->where('id', $id)->first();

        if (!$notification) {
            return jsonResponse(false, "Notification Not Found", null, 404);
        }

        $notification->update(['read_at' => null]);

        return jsonResponse(true, "Notification Marked As Unread");
    }

    public function destroy($id)
    {
        $user = auth()->user();

        $notification = $user->notifications()->where('id', $id)->first();

        if (!$notification) {
            return jsonResponse(false, "Notification Not Found", null, 404);
        }

        $notification->delete();

        return jsonResponse(true, "Notification Deleted");
    }
}
