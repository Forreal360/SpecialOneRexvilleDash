<?php

namespace App\Livewire\V1\Panel\Appointment;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Livewire\Concerns\HandlesActionResults;
use App\Services\V1\AppointmentService;
use App\Actions\V1\Appointment\CompleteAppointmentAction;
use Illuminate\Support\Facades\DB;

class CompleteAppointmentComponent extends Component
{
    use HandlesActionResults;

    public $appointmentId;
    public $appointment;
    public $selectedServices = [];
    public $completionNotes = '';

    private $appointmentService;
    private $completeAppointmentAction;

    public function boot(
        AppointmentService $appointmentService,
        CompleteAppointmentAction $completeAppointmentAction
    ) {
        $this->appointmentService = $appointmentService;
        $this->completeAppointmentAction = $completeAppointmentAction;
    }

    public function mount($id)
    {
        $this->appointmentId = $id;
        $this->loadAppointment();
        $this->initializeSelectedServices();
    }

    public function loadAppointment()
    {
        $this->appointment = $this->appointmentService->findByIdOrFail($this->appointmentId);
        $this->appointment->load(['client', 'vehicle.make', 'vehicle.model', 'services']);

        if ($this->appointment->status !== 'confirmed') {
            session()->flash('error', 'Solo se pueden completar agendamientos confirmados');
            return redirect()->route('v1.panel.appointments.index');
        }
    }

    public function initializeSelectedServices()
    {
        // Inicializar servicios según su status actual en la tabla pivot
        foreach ($this->appointment->services as $service) {
            // Si ya hay un status en la tabla pivot, usarlo; si no, marcar como seleccionado
            $pivotStatus = $service->pivot->status ?? 'A';
            $this->selectedServices[$service->id] = ($pivotStatus === 'A');
        }
    }

    public function completeAppointment()
    {
        $this->validate([
            'completionNotes' => 'nullable|string|max:1000',
        ], [
            'completionNotes.max' => 'Las notas de finalización no pueden tener más de 1000 caracteres',
        ]);

        try {
            DB::transaction(function () {
                // Actualizar el status de los servicios según la selección
                foreach ($this->appointment->services as $service) {
                    $status = isset($this->selectedServices[$service->id]) && $this->selectedServices[$service->id] ? 'A' : 'I';

                    DB::table('appointment_services')
                        ->where('appointment_id', $this->appointmentId)
                        ->where('service_id', $service->id)
                        ->update(['status' => $status]);
                }

                // Completar el appointment
                $result = $this->executeAction($this->completeAppointmentAction, [
                    'id' => $this->appointmentId,
                    'completion_notes' => $this->completionNotes,
                ], true);

                if ($result->isSuccess()) {
                    return redirect()->route('v1.panel.appointments.index')
                        ->with('success', 'Agendamiento completado exitosamente');
                }
            });
        } catch (\Exception $e) {
            session()->flash('error', 'Error al completar el agendamiento');
        }
    }

    public function selectAllServices()
    {
        foreach ($this->appointment->services as $service) {
            $this->selectedServices[$service->id] = true;
        }
    }

    public function deselectAllServices()
    {
        foreach ($this->appointment->services as $service) {
            $this->selectedServices[$service->id] = false;
        }
    }

    #[Computed]
    public function selectedServicesCount()
    {
        return collect($this->selectedServices)->filter()->count();
    }

    public function cancel()
    {
        return redirect()->route('v1.panel.appointments.index');
    }

    public function render()
    {
        return view('v1.panel.appointment.complete-appointment-component')
            ->layout('v1.layouts.panel.main');
    }
}
