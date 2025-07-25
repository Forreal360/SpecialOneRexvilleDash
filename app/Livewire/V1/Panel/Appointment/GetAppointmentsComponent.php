<?php

namespace App\Livewire\V1\Panel\Appointment;

use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Concerns\HandlesActionResults;
use App\Models\Appointment;
use App\Services\V1\AppointmentService;
use App\Actions\V1\Appointment\ConfirmAppointmentAction;
use App\Actions\V1\Appointment\CancelAppointmentAction;
use App\Actions\V1\Appointment\CompleteAppointmentAction;

class GetAppointmentsComponent extends Component
{
    use WithPagination, HandlesActionResults;

    public $search = '';
    public $perPage = 15;
    public $status = '';
    public $sortBy = 'appointment_datetime';
    public $sortDirection = 'desc';
    public $date_from = '';
    public $date_to = '';
    public $client_filter = '';

    private $appointmentService;
    private $confirmAppointmentAction;
    private $cancelAppointmentAction;
    private $completeAppointmentAction;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 15],
        'status' => ['except' => ''],
        'sortBy' => ['except' => 'appointment_datetime'],
        'sortDirection' => ['except' => 'desc'],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'client_filter' => ['except' => ''],
    ];

    protected $rules = [
        'perPage' => 'in:5,10,15,20,50,100',
        'search' => 'nullable|string|max:255',
        'status' => 'nullable|in:pending,confirmed,cancelled,completed',
        'date_from' => 'nullable|date',
        'date_to' => 'nullable|date|after_or_equal:date_from',
    ];

    public function boot(
        AppointmentService $appointmentService,
        ConfirmAppointmentAction $confirmAppointmentAction,
        CancelAppointmentAction $cancelAppointmentAction,
        CompleteAppointmentAction $completeAppointmentAction
    ) {
        $this->appointmentService = $appointmentService;
        $this->confirmAppointmentAction = $confirmAppointmentAction;
        $this->cancelAppointmentAction = $cancelAppointmentAction;
        $this->completeAppointmentAction = $completeAppointmentAction;
    }

    public function resetFilters()
    {
        $this->reset([
            'search', 'status', 'date_from', 'date_to', 'client_filter'
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function updatingClientFilter()
    {
        $this->resetPage();
    }

    public function sort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Direct action methods following the established pattern
    public function confirmAppointment($appointmentId)
    {
        $result = $this->executeAction($this->confirmAppointmentAction, [
            'id' => $appointmentId
        ], true);

        if ($result->isSuccess()) {
            session()->flash('success', __('panel.appointment_confirmed_successfully'));
        }
    }

    public function cancelAppointment($appointmentId)
    {
        $result = $this->executeAction($this->cancelAppointmentAction, [
            'id' => $appointmentId,
            'cancellation_reason' => 'Cancelado desde el panel administrativo'
        ], true);

        if ($result->isSuccess()) {
            session()->flash('success', __('panel.appointment_cancelled_successfully'));
        }
    }

    public function completeAppointment($appointmentId)
    {
        $result = $this->executeAction($this->completeAppointmentAction, [
            'id' => $appointmentId,
            'completion_notes' => 'Completado desde el panel administrativo'
        ], true);

        if ($result->isSuccess()) {
            session()->flash('success', __('panel.appointment_completed_successfully'));
        }
    }

    public function render()
    {
        $appointments = Appointment::query()
            ->with(['client', 'vehicle.make', 'vehicle.model', 'service'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('notes', 'like', '%' . $this->search . '%')
                      ->orWhereHas('client', function ($clientQuery) {
                          $clientQuery->whereRaw("CONCAT(name, ' ', last_name) LIKE ?", ['%' . $this->search . '%'])
                                     ->orWhere('email', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('service', function ($serviceQuery) {
                          $serviceQuery->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->date_from && $this->date_to, function ($query) {
                $start_date = $this->date_from . ' ' . '00:00:00';
                $end_date = $this->date_to . ' ' . '23:59:59';
                $start_date = dateToUTC($start_date, session('timezone'));
                $end_date = dateToUTC($end_date, session('timezone'));
                $query->whereBetween('appointment_datetime', [$start_date, $end_date]);
            })
            ->when($this->client_filter, function ($query) {
                $query->where('client_id', $this->client_filter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $statusOptions = [
            'pending' => __('panel.appointment_status_pending'),
            'confirmed' => __('panel.appointment_status_confirmed'),
            'cancelled' => __('panel.appointment_status_cancelled'),
            'completed' => __('panel.appointment_status_completed'),
        ];

        $perPageOptions = [5, 10, 15, 20, 50, 100];

        // Get status counts for dashboard stats using the service
        $statusCounts = $this->appointmentService->getStatusCounts();

        return view('v1.panel.appointment.get-appointments-component', compact(
            'appointments',
            'statusOptions',
            'perPageOptions',
            'statusCounts'
        ));
    }
}
