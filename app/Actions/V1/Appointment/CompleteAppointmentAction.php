<?php

declare(strict_types=1);

namespace App\Actions\V1\Appointment;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\AppointmentService;
use App\Actions\V1\Client\Service\CreateClientServiceAction;
use App\Notifications\AppointmentStatusChangedNotification;

class CompleteAppointmentAction extends Action
{
    /**
     * Constructor - Inject dependencies here
     */
    public function __construct(
        private AppointmentService $appointmentService,
        private CreateClientServiceAction $createClientServiceAction
    ) {
        //
    }

    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {
        // Validate input data
        $validated = $this->validateData($data, [
            'id' => 'required|integer|exists:appointments,id',
            'completion_notes' => 'nullable|string|max:1000',
        ], [
            'id.required' => 'El ID del agendamiento es obligatorio',
            'id.exists' => 'El agendamiento no existe',
            'completion_notes.max' => 'Las notas de finalización no pueden tener más de 1000 caracteres',
        ]);

        // Business logic with transaction
        return DB::transaction(function () use ($validated) {
            // Find the appointment
            $appointment = $this->appointmentService->findByIdOrFail((int)$validated['id']);

            // Check if appointment can be completed (only confirmed can be completed)
            if ($appointment->status !== 'confirmed') {
                return $this->errorResult(
                    message: 'Solo se pueden completar agendamientos confirmados',
                    statusCode: 400
                );
            }

            // Store previous status for notification
            $previousStatus = $appointment->status;

            // Prepare update data
            $updateData = ['status' => 'completed'];

            // Add completion notes if provided
            if (!empty($validated['completion_notes'])) {
                $currentNotes = $appointment->notes ?? '';
                $completionNote = "COMPLETADO: " . $validated['completion_notes'];
                $updateData['notes'] = $currentNotes ? $currentNotes . "\n\n" . $completionNote : $completionNote;
            }

            // Update the appointment
            $updated = $this->appointmentService->update((int)$validated['id'], $updateData);

            if (!$updated) {
                return $this->errorResult(
                    message: 'Error al completar el agendamiento',
                    statusCode: 500
                );
            }

            // Create ClientService record only for active services (status 'A')
            $appointmentDate = \Carbon\Carbon::parse($appointment->appointment_datetime);
            $clientServiceData = [
                'client_id' => $appointment->client_id,
                'vehicle_id' => $appointment->vehicle_id,
                'date' => $appointmentDate->format('m/d/Y'), // Format required by CreateClientServiceAction
            ];

            // Get updated appointment with fresh pivot data
            $updatedAppointment = $this->appointmentService->findByIdOrFail((int)$validated['id']);
            $updatedAppointment->load(['services' => function($query) {
                $query->withPivot('status');
            }]);

            foreach ($updatedAppointment->services as $service) {
                // Only create ClientService for services with status 'A' (active)
                if ($service->pivot->status === 'A') {
                    $clientServiceData['service_id'] = $service->id;
                    $clientServiceResult = $this->createClientServiceAction->execute($clientServiceData);
                    if (!$clientServiceResult->isSuccess()) {
                        return $this->errorResult(
                            message: 'Error al crear el servicio del cliente',
                            statusCode: 500
                        );
                    }
                }
            }

            // Get updated appointment with relationships
            $updatedAppointment = $this->appointmentService->findByIdOrFail((int)$validated['id']);
            $updatedAppointment->load(['client', 'vehicle.make', 'vehicle.model', 'services']);

            // Send notification to client about status change
            try {
                $updatedAppointment->client->notify(
                    new AppointmentStatusChangedNotification($updatedAppointment, $previousStatus, 'completed')
                );
            } catch (\Exception $e) {
                // Log the error but don't fail the action
                \Log::warning('Failed to send appointment status notification', [
                    'appointment_id' => $validated['id'],
                    'previous_status' => $previousStatus,
                    'new_status' => 'completed',
                    'error' => $e->getMessage()
                ]);
            }

            return $this->successResult(
                data: $updatedAppointment,
                message: 'Agendamiento completado exitosamente'
            );
        });
    }
}
