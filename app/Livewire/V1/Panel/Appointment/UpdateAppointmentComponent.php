<?php

namespace App\Livewire\V1\Panel\Appointment;

use Livewire\Component;
use App\Livewire\Concerns\HandlesActionResults;
use App\Services\V1\AppointmentService;
use App\Actions\V1\Appointment\UpdateAppointmentAction;
use Carbon\Carbon;

class UpdateAppointmentComponent extends Component
{
    use HandlesActionResults;

    public $appointmentId;
    public $appointment;

    // Form fields
    public $appointment_date;
    public $appointment_time;
    public $timezone;
    public $notes;

    // Display fields (read-only)
    public $client_name;
    public $client_email;
    public $vehicle_info;
    public $service_name;
    public $current_status;

    private $appointmentService;
    private $updateAppointmentAction;

    protected $rules = [
        'appointment_date' => 'required|date|after_or_equal:today',
        'appointment_time' => 'required|date_format:H:i',
        'timezone' => 'nullable|string|max:50',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'appointment_date.required' => 'La fecha del agendamiento es obligatoria',
        'appointment_date.date' => 'La fecha debe tener un formato válido',
        'appointment_date.after_or_equal' => 'La fecha debe ser hoy o en el futuro',
        'appointment_time.required' => 'La hora del agendamiento es obligatoria',
        'appointment_time.date_format' => 'La hora debe tener el formato HH:MM',
        'timezone.max' => 'La zona horaria no puede tener más de 50 caracteres',
        'notes.max' => 'Las notas no pueden tener más de 1000 caracteres',
    ];

    public function boot(
        AppointmentService $appointmentService,
        UpdateAppointmentAction $updateAppointmentAction
    ) {
        $this->appointmentService = $appointmentService;
        $this->updateAppointmentAction = $updateAppointmentAction;
    }

    public function mount($id)
    {
        $this->appointmentId = $id;
        $this->loadAppointment();
    }

    public function loadAppointment()
    {
        $this->appointment = $this->appointmentService->findByIdOrFail($this->appointmentId);
        $this->appointment->load(['client', 'vehicle.make', 'vehicle.model', 'service']);

        // Check if appointment can be updated
        if (!in_array($this->appointment->status, ['pending', 'confirmed'])) {
            abort(403, 'No se puede modificar un agendamiento que está cancelado o completado');
        }

        // Set form fields
        $appointmentDateTime = Carbon::parse($this->appointment->appointment_datetime);
        $this->appointment_date = $appointmentDateTime->format('Y-m-d');
        $this->appointment_time = $appointmentDateTime->format('H:i');
        $this->timezone = $this->appointment->timezone;
        $this->notes = $this->appointment->notes;

        // Set display fields
        $this->client_name = $this->appointment->client->name . ' ' . $this->appointment->client->last_name;
        $this->client_email = $this->appointment->client->email;
        $this->vehicle_info = ($this->appointment->vehicle->make->name ?? '') . ' ' .
                             ($this->appointment->vehicle->model->name ?? '') . ' ' .
                             $this->appointment->vehicle->year;
        $this->service_name = $this->appointment->service->name;
        $this->current_status = $this->appointment->status;
    }

    public function updateAppointment()
    {
        $this->validate();

        // Combine date and time
        $appointmentDateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $this->appointment_date . ' ' . $this->appointment_time
        )->toDateTimeString();

        $result = $this->executeAction($this->updateAppointmentAction, [
            'id' => $this->appointmentId,
            'appointment_datetime' => $appointmentDateTime,
            'timezone' => $this->timezone,
            'notes' => $this->notes,
        ], true);

        if ($result->isSuccess()) {
            return redirect()->route('v1.panel.appointments.index')
                ->with('success', 'Agendamiento actualizado exitosamente');
        }
    }

    public function render()
    {
        return view('v1.panel.appointment.update-appointment-component');
    }
}
