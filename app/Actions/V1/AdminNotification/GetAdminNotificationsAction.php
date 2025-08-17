<?php

namespace App\Actions\V1\AdminNotification;

use App\Actions\V1\Action;
use App\Actions\V1\ActionResult;
use App\Services\V1\AdminNotificationService;

class GetAdminNotificationsAction extends Action
{
    public function __construct(
        private AdminNotificationService $adminNotificationService
    ) {}

    public function handle($data = []): ActionResult
    {
        $validated = $this->validateData($data, [
            'per_page' => 'nullable|integer|min:1|max:100',
            'current_page' => 'nullable|integer|min:1'
        ]);

        $notifications = $this->adminNotificationService->getNotifications(
            per_page: $validated['per_page'] ?? null,
            current_page: $validated['current_page'] ?? 1
        );

        return $this->successResult(
            data: $notifications,
            message: 'Notificaciones obtenidas exitosamente'
        );
    }
}