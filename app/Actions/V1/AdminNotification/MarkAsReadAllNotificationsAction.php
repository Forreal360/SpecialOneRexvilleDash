<?php

namespace App\Actions\V1\AdminNotification;

use App\Actions\V1\Action;
use App\Actions\V1\ActionResult;
use App\Services\V1\AdminNotificationService;

class MarkAsReadAllNotificationsAction extends Action
{
    public function __construct(
        private AdminNotificationService $adminNotificationService
    ) {}

    public function handle(): ActionResult
    {
        $result = $this->adminNotificationService->markAsReadAllNotifications();

        return $this->successResult(
            message: 'Todas las notificaciones han sido marcadas como leídas'
        );
    }
}