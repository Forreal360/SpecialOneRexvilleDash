<?php

namespace App\Livewire\V1\Panel\Appointment;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Livewire\Concerns\HandlesActionResults;
use App\Services\V1\AppointmentService;
use App\Actions\V1\Appointment\ConfirmAppointmentAction;
use Illuminate\Support\Facades\DB;

class ConfirmAppointmentComponent extends Component
{
    use HandlesActionResults;

    public $appointmentId;
    public $appointment;
    public $selectedServices = [];

    private $appointmentService;
    private $confirmAppointmentAction;

    public function boot(
        AppointmentService $appointmentService,
        ConfirmAppointmentAction $confirmAppointmentAction
    ) {
        $this->appointmentService = $appointmentService;
        $this->confirmAppointmentAction = $confirmAppointmentAction;
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

        if ($this->appointment->status !== 'pending') {
            session()->flash('error', 'Solo se pueden confirmar agendamientos pendientes');
            return redirect()->route('v1.panel.appointments.index');
        }
    }

    public function initializeSelectedServices()
    {
        // Inicializar todos los servicios como seleccionados (status 'A')
        foreach ($this->appointment->services as $service) {
            $this->selectedServices[$service->id] = true;
        }
    }

    public function confirmAppointment()
    {
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

                // Confirmar el appointment
                $result = $this->executeAction($this->confirmAppointmentAction, [
                    'id' => $this->appointmentId,
                ], true);

                if ($result->isSuccess()) {
                    return redirect()->route('v1.panel.appointments.index')
                        ->with('success', 'Agendamiento confirmado exitosamente');
                }
            });
        } catch (\Exception $e) {
            session()->flash('error', 'Error al confirmar el agendamiento');
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
        return view('v1.panel.appointment.confirm-appointment-component')
            ->layout('v1.layouts.panel.main');
    }
}
