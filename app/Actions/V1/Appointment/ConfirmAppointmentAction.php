<?php

declare(strict_types=1);

namespace App\Actions\V1\Appointment;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\AppointmentService;
use App\Notifications\AppointmentStatusChangedNotification;

class ConfirmAppointmentAction extends Action
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
        ], [
            'id.required' => 'El ID del agendamiento es obligatorio',
            'id.exists' => 'El agendamiento no existe',
        ]);

        // Business logic with transaction
        return DB::transaction(function () use ($validated) {
            // Find the appointment
            $appointment = $this->appointmentService->findByIdOrFail( (int) $validated['id']);

            // Check if appointment can be confirmed (only pending can be confirmed)
            if ($appointment->status !== 'pending') {
                return $this->errorResult(
                    message: 'Solo se pueden confirmar agendamientos pendientes',
                    statusCode: 400
                );
            }

            // Store previous status for notification
            $previousStatus = $appointment->status;

            // Confirm the appointment
            $confirmed = $this->appointmentService->confirm((int)$validated['id']);

            if (!$confirmed) {
                return $this->errorResult(
                    message: 'Error al confirmar el agendamiento',
                    statusCode: 500
                );
            }

            // Get updated appointment with relationships
            $updatedAppointment = $this->appointmentService->findByIdOrFail((int)$validated['id']);
            $updatedAppointment->load(['client', 'vehicle.make', 'vehicle.model', 'services']);

            // Send notification to client about status change
            try {
                $updatedAppointment->client->notify(
                    new AppointmentStatusChangedNotification($updatedAppointment, $previousStatus, 'confirmed')
                );
            } catch (\Exception $e) {
                // Log the error but don't fail the action
                \Log::warning('Failed to send appointment status notification', [
                    'appointment_id' => $validated['id'],
                    'previous_status' => $previousStatus,
                    'new_status' => 'confirmed',
                    'error' => $e->getMessage()
                ]);
            }

            return $this->successResult(
                data: $updatedAppointment,
                message: 'Agendamiento confirmado exitosamente'
            );
        });
    }
}
