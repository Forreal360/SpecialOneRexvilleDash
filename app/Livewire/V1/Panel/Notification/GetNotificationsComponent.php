<?php

namespace App\Livewire\V1\Panel\Notification;

use Livewire\Component;
use App\Services\V1\AdminNotificationService;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GetNotificationsComponent extends Component
{
    public $per_page = 20;
    public $current_page = 1;
    public $last_page;
    public $first_load = true;
    public $unread_notifications = 0;

    protected $adminNotificationService;

    public function boot(AdminNotificationService $adminNotificationService)
    {
        $this->adminNotificationService = $adminNotificationService;
        $this->unread_notifications = $this->adminNotificationService->getUnreadNotificationsCount();
    }

    public function render()
    {
        return view('v1.panel.notification.get-notifications-component');
    }

    #[Renderless]
    public function updateNotifications()
    {
        $this->dispatch('update-notifications-count');
    }

    public function doAction($notification)
    {
        if($notification["read"] == "N"){
            $this->adminNotificationService->markAsReadNotification($notification["id"]);
        }

        if($notification["action"] == "redirect") {
            $payload = json_decode($notification["payload"], true);
            if (isset($payload["route"])) {
                return redirect()->to($payload["route"]);
            }
        }
    }

    #[Renderless]
    public function getNotifications()
    {
        if($this->unread_notifications > 0) {
            $this->dispatch('update-notifications-count');
        }

        if($this->first_load) {
            $this->first_load = false;

            $notifications = $this->adminNotificationService->getNotifications(
                per_page: $this->per_page,
                current_page: $this->current_page
            );

            $notifications->map(function ($notification){
                $notification->formatted_date = $this->formatNotificationDate($notification->date);
            });

            $notifications = $notifications->toArray();
            $this->last_page = $notifications["last_page"];
            $this->dispatch('list-notifications', $notifications);
        }
    }

    #[Renderless]
    public function getMoreNotifications()
    {
        $this->current_page++;

        $notifications = $this->adminNotificationService->getNotifications(
            per_page: $this->per_page,
            current_page: $this->current_page
        );

        $notifications->map(function ($notification){
            $notification->formatted_date = $this->formatNotificationDate($notification->date);
        });

        $notifications = $notifications->toArray();
        $this->last_page = $notifications["last_page"];
        $this->dispatch('list-notifications', $notifications);
    }

    #[Renderless]
    public function markAsReadAllNotifications()
    {
        $this->adminNotificationService->markAsReadAllNotifications();

        $this->unread_notifications = 0;
        $this->dispatch('update-notifications-count');
        $this->dispatch('mark-as-read-all-notifications');
    }

    private function formatNotificationDate($date)
    {
        // Si ya es un string que no es una fecha válida, retornarlo tal como está
        if (is_string($date)) {
            // Verificar si es un string ya formateado (contiene palabras como "Ahora", "Hace")
            if (strpos($date, 'Ahora') !== false || strpos($date, 'Hace') !== false ||
                !preg_match('/^\d{4}-\d{2}-\d{2}|\d{2}-\d{2}-\d{4}|\d{2}\/\d{2}\/\d{4}/', $date)) {
                return $date;
            }
        }

        try {
            // Obtener timezone de la sesión
            $userTimezone = session('timezone', 'UTC');
            
            $now = Carbon::now($userTimezone);
            $notificationTime = $date instanceof Carbon ? $date->setTimezone($userTimezone) : Carbon::parse($date)->setTimezone($userTimezone);

            $diffInMinutes = round(abs($now->diffInMinutes($notificationTime)));
            $diffInHours = round(abs($now->diffInHours($notificationTime)));

            // Si es menos de 2 minutos, mostrar "Ahora"
            if ($diffInMinutes < 2) {
                return 'Ahora';
            }

            // Si es menos de 60 minutos, mostrar "hace X minutos"
            if ($diffInMinutes < 60) {
                return "Hace {$diffInMinutes} minutos";
            }

            // Si es menos de 24 horas, mostrar "hace X horas"
            if ($diffInHours < 24) {
                return "Hace {$diffInHours} horas";
            }

            // Si es más de 1 día, mostrar la fecha en formato m-d-Y H:i
            return $notificationTime->format('m-d-Y H:i');
        } catch (\Exception $e) {
            // Si hay un error al parsear la fecha, retornar la fecha original
            return is_string($date) ? $date : $date->format('m-d-Y H:i');
        }
    }

    #[Renderless]
    public function loadNewNotification($notification_id)
    {
        $notification = $this->adminNotificationService->getNewNotification($notification_id);

        if($notification){
            $this->unread_notifications = $this->adminNotificationService->getUnreadNotificationsCount();

            $notification->formatted_date = $this->formatNotificationDate($notification->date);

            $this->dispatch('new-notification', [
                "notification" => $notification,
                "unread_notifications" => $this->unread_notifications,
                "first_load" => false // Always false since this is a new notification
            ]);
        }
    }
}
