<?php

declare(strict_types=1);

namespace App\Actions\V1\Appointment;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\AppointmentService;
use App\Notifications\AppointmentStatusChangedNotification;

class CancelAppointmentAction extends Action
{
    /**
     * Constructor - Inject dependencies here
     */
    public function __construct(private AppointmentService $appointmentService)
    {
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
            'cancellation_reason' => 'nullable|string|max:500',
        ], [
            'id.required' => 'El ID del agendamiento es obligatorio',
            'id.exists' => 'El agendamiento no existe',
            'cancellation_reason.max' => 'El motivo de cancelación no puede tener más de 500 caracteres',
        ]);

        // Business logic with transaction
        return DB::transaction(function () use ($validated) {
            // Find the appointment
            $appointment = $this->appointmentService->findByIdOrFail($validated['id']);

            // Check if appointment can be cancelled (pending and confirmed can be cancelled)
            if (!in_array($appointment->status, ['pending', 'confirmed'])) {
                return $this->errorResult(
                    message: 'No se puede cancelar un agendamiento que ya está cancelado o completado',
                    statusCode: 400
                );
            }

            // Store previous status for notification
            $previousStatus = $appointment->status;

            // Prepare update data
            $updateData = ['status' => 'cancelled'];

            // Add cancellation reason to notes if provided
            if (!empty($validated['cancellation_reason'])) {
                $currentNotes = $appointment->notes ?? '';
                $cancellationNote = "CANCELADO: " . $validated['cancellation_reason'];
                $updateData['notes'] = $currentNotes ? $currentNotes . "\n\n" . $cancellationNote : $cancellationNote;
            }

            // Update the appointment
            $updated = $this->appointmentService->update($validated['id'], $updateData);

            if (!$updated) {
                return $this->errorResult(
                    message: 'Error al cancelar el agendamiento',
                    statusCode: 500
                );
            }

            // Get updated appointment with relationships
            $updatedAppointment = $this->appointmentService->findByIdOrFail($validated['id']);
            $updatedAppointment->load(['client', 'vehicle.make', 'vehicle.model', 'service']);

            // Send notification to client about status change
            try {
                $updatedAppointment->client->notify(
                    new AppointmentStatusChangedNotification($updatedAppointment, $previousStatus, 'cancelled')
                );
            } catch (\Exception $e) {
                // Log the error but don't fail the action
                \Log::warning('Failed to send appointment status notification', [
                    'appointment_id' => $validated['id'],
                    'previous_status' => $previousStatus,
                    'new_status' => 'cancelled',
                    'error' => $e->getMessage()
                ]);
            }

            return $this->successResult(
                data: $updatedAppointment,
                message: 'Agendamiento cancelado exitosamente'
            );
        });
    }
}
