<?php

namespace App\Services\V1;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;

class AdminNotificationService extends Service
{
    public function __construct()
    {
        $this->model = AdminNotification::class;
        $this->searchableFields = ['title', 'message'];
        $this->per_page = 15;
    }

    public function getNotifications($per_page = null, $current_page = 1)
    {
        return $this->model::where('admin_id', Auth::guard('admin')->id())
            ->orderBy('date', 'desc')
            ->paginate($per_page ?? $this->per_page, ['*'], 'page', $current_page);
    }

    public function getUnreadNotificationsCount()
    {
        return $this->model::where('admin_id', Auth::guard('admin')->id())
            ->where('read', 'N')
            ->where('status', 'A')
            ->count();
    }

    public function markAsReadNotification($notification_id)
    {
        return $this->model::where('id', $notification_id)
            ->where('admin_id', Auth::guard('admin')->id())
            ->update(['read' => 'Y']);
    }

    public function markAsReadAllNotifications()
    {
        return $this->model::where('admin_id', Auth::guard('admin')->id())
            ->where('read', 'N')
            ->update(['read' => 'Y']);
    }

    public function getNewNotification($notification_id)
    {
        return $this->model::where('id', $notification_id)
            ->where('admin_id', Auth::guard('admin')->id())
            ->first();
    }

    public function createNotification($admin_id, $title, $message, $action = 'none', $payload = [])
    {
        return $this->model::create([
            'admin_id' => $admin_id,
            'title' => $title,
            'message' => $message,
            'date' => now(),
            'action' => $action,
            'payload' => json_encode($payload),
            'read' => 'N',
            'status' => 'A'
        ]);
    }
}