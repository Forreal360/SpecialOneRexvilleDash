<?php

declare(strict_types=1);

namespace App\Actions\V1\Appointment;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\AppointmentService;
use Carbon\Carbon;

class UpdateAppointmentAction extends Action
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
        $this->validatePermissions([
            "appointments.update"
        ]);
        // Validate input data
        $validated = $this->validateData($data, [
            'id' => 'required|integer|exists:appointments,id',
            'appointment_datetime' => 'required|date|after:now',
            'timezone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Business logic with transaction
        return DB::transaction(function () use ($validated) {
            // Find the appointment
            $appointment = $this->appointmentService->findByIdOrFail((int)$validated['id']);

            // Check if appointment can be updated (only pending and confirmed can be updated)
            if (!in_array($appointment->status, ['pending', 'confirmed'])) {
                return $this->errorResult(
                    message: 'No se puede modificar un agendamiento que está cancelado o completado',
                    statusCode: 400
                );
            }

            // Prepare update data
            $updateData = [
                'appointment_datetime' => $validated['appointment_datetime'],
                'notes' => $validated['notes'] ?? $appointment->notes,
            ];

            // Add timezone if provided
            if (isset($validated['timezone'])) {
                $updateData['timezone'] = $validated['timezone'];
            }

            // Update the appointment
            $updatedAppointment = $this->appointmentService->update((int)$validated['id'], $updateData);

            if (!$updatedAppointment) {
                return $this->errorResult(
                    message: 'Error al actualizar el agendamiento',
                    statusCode: 500
                );
            }

            // Load relationships for response
            $updatedAppointment->load(['client', 'vehicle.make', 'vehicle.model', 'services']);

            return $this->successResult(
                data: $updatedAppointment,
                message: 'Agendamiento actualizado exitosamente'
            );
        });
    }
}
