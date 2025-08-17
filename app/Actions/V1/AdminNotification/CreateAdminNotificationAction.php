<?php

namespace App\Actions\V1\AdminNotification;

use App\Actions\V1\Action;
use App\Actions\V1\ActionResult;
use App\Services\V1\AdminNotificationService;
use App\Services\V1\FirestoreService;
use Illuminate\Support\Facades\DB;

class CreateAdminNotificationAction extends Action
{
    public function __construct(
        private AdminNotificationService $adminNotificationService,
        private FirestoreService $firestoreService
    ) {}

    public function handle($data): ActionResult
    {
        $validated = $this->validateData($data, [
            'admin_id' => 'required|integer|exists:admins,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'action' => 'nullable|string|max:255',
            'payload' => 'nullable|array'
        ], [
            'admin_id.required' => 'El ID del administrador es obligatorio',
            'admin_id.exists' => 'El administrador no existe',
            'title.required' => 'El título es obligatorio',
            'message.required' => 'El mensaje es obligatorio'
        ]);

        return DB::transaction(function () use ($validated) {
            $notification = $this->adminNotificationService->createNotification(
                admin_id: $validated['admin_id'],
                title: $validated['title'],
                message: $validated['message'],
                action: $validated['action'] ?? 'none',
                payload: $validated['payload'] ?? []
            );

            $this->firestoreService->updateFirestoreDocument(
                'admin-notification-trigger',
                (string) $validated['admin_id'],
                [
                    'notification_id' => $notification->id,
                    'updated_at' => now()->toISOString()
                ]
            );

            return $this->successResult(
                data: $notification,
                message: 'Notificación creada exitosamente'
            );
        });
    }
}