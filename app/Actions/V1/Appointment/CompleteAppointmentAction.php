<?php

declare(strict_types=1);

namespace App\Actions\V1\Appointment;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Services\V1\AppointmentService;
use App\Actions\V1\Client\Service\CreateClientServiceAction;

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
            $appointment = $this->appointmentService->findByIdOrFail($validated['id']);

            // Check if appointment can be completed (only confirmed can be completed)
            if ($appointment->status !== 'confirmed') {
                return $this->errorResult(
                    message: 'Solo se pueden completar agendamientos confirmados',
                    statusCode: 400
                );
            }

            // Prepare update data
            $updateData = ['status' => 'completed'];

            // Add completion notes if provided
            if (!empty($validated['completion_notes'])) {
                $currentNotes = $appointment->notes ?? '';
                $completionNote = "COMPLETADO: " . $validated['completion_notes'];
                $updateData['notes'] = $currentNotes ? $currentNotes . "\n\n" . $completionNote : $completionNote;
            }

            // Update the appointment
            $updated = $this->appointmentService->update($validated['id'], $updateData);

            if (!$updated) {
                return $this->errorResult(
                    message: 'Error al completar el agendamiento',
                    statusCode: 500
                );
            }

            // Create ClientService record when appointment is completed
            $appointmentDate = \Carbon\Carbon::parse($appointment->appointment_datetime);
            $clientServiceData = [
                'client_id' => $appointment->client_id,
                'vehicle_id' => $appointment->vehicle_id,
                'service_id' => $appointment->service_id,
                'date' => $appointmentDate->format('m/d/Y'), // Format required by CreateClientServiceAction
            ];

            // Execute CreateClientServiceAction
            $clientServiceResult = $this->createClientServiceAction->execute($clientServiceData);

            if (!$clientServiceResult->isSuccess()) {
                // If client service creation fails, we still want to complete the appointment
                // but log the error for visibility
                \Log::warning('Failed to create ClientService after completing appointment', [
                    'appointment_id' => $validated['id'],
                    'client_service_data' => $clientServiceData,
                    'error' => $clientServiceResult->getMessage(),
                    'errors' => $clientServiceResult->getErrors()
                ]);
            }

            // Get updated appointment with relationships
            $updatedAppointment = $this->appointmentService->findByIdOrFail($validated['id']);
            $updatedAppointment->load(['client', 'vehicle.make', 'vehicle.model', 'service']);

            return $this->successResult(
                data: $updatedAppointment,
                message: 'Agendamiento completado exitosamente'
            );
        });
    }
}
