<?php

namespace App\Actions\V1\AdminNotification;

use App\Actions\V1\Action;
use App\Actions\V1\ActionResult;
use App\Services\V1\AdminNotificationService;

class MarkAsReadNotificationAction extends Action
{
    public function __construct(
        private AdminNotificationService $adminNotificationService
    ) {}

    public function handle($data): ActionResult
    {
        $validated = $this->validateData($data, [
            'notification_id' => 'required|integer|exists:admin_notifications,id'
        ], [
            'notification_id.required' => 'El ID de la notificación es obligatorio',
            'notification_id.exists' => 'La notificación no existe'
        ]);

        $result = $this->adminNotificationService->markAsReadNotification($validated['notification_id']);

        if ($result) {
            return $this->successResult(
                message: 'Notificación marcada como leída'
            );
        }

        return $this->errorResult(
            message: 'No se pudo marcar la notificación como leída'
        );
    }
}