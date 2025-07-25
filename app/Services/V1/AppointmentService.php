<?php

declare(strict_types=1);

namespace App\Services\V1;

use App\Models\Appointment;
use App\Services\V1\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AppointmentService extends Service
{
    /**
     * Constructor - Set the model class
     */
    public function __construct()
    {
        $this->modelClass = Appointment::class;

        // Configure searchable fields for this service
        $this->searchableFields = [
            'notes',
        ];

        // Configure pagination
        $this->per_page = 15;
    }

    /**
     * Get paginated appointments with relationships
     *
     * @param array $filters
     * @param int $page
     * @param string $search
     * @param string $sort_by
     * @param string $sort_direction
     * @param int|null $per_page
     * @return LengthAwarePaginator
     */
    public function getPaginated(
        array $filters = [],
        int $page = 1,
        string $search = '',
        string $sort_by = 'appointment_datetime',
        string $sort_direction = 'desc',
        ?int $per_page = null
    ): LengthAwarePaginator {
        $query = $this->modelClass::query()
            ->with(['client', 'vehicle.make', 'vehicle.model', 'service']);

        // Apply search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', '%' . $search . '%')
                  ->orWhereHas('client', function ($clientQuery) use ($search) {
                      $clientQuery->where('name', 'like', '%' . $search . '%')
                                 ->orWhere('email', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('service', function ($serviceQuery) use ($search) {
                      $serviceQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Apply filters
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                if ($field === 'status' && is_array($value)) {
                    $query->whereIn('status', $value);
                } elseif ($field === 'date_from' && !empty($value)) {
                    $query->where('appointment_datetime', '>=', $value);
                } elseif ($field === 'date_to' && !empty($value)) {
                    $query->where('appointment_datetime', '<=', $value);
                } elseif ($field === 'client_id' && !empty($value)) {
                    $query->where('client_id', $value);
                } elseif (!empty($value)) {
                    if (is_array($value)) {
                        $query->whereIn($field, $value);
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        }

        // Apply sorting
        $query->orderBy($sort_by, $sort_direction);

        return $query->paginate($per_page ?? $this->per_page, ['*'], 'page', $page);
    }

    /**
     * Get appointments by status
     *
     * @param string $status
     * @return Collection
     */
    public function getByStatus(string $status): Collection
    {
        return $this->modelClass::byStatus($status)
            ->with(['client', 'vehicle.make', 'vehicle.model', 'service'])
            ->orderBy('appointment_datetime', 'asc')
            ->get();
    }

    /**
     * Get pending appointments
     *
     * @return Collection
     */
    public function getPending(): Collection
    {
        return $this->getByStatus('pending');
    }

    /**
     * Get confirmed appointments
     *
     * @return Collection
     */
    public function getConfirmed(): Collection
    {
        return $this->getByStatus('confirmed');
    }

    /**
     * Get appointments for today
     *
     * @return Collection
     */
    public function getTodayAppointments(): Collection
    {
        $today = now()->startOfDay();
        $endToday = now()->endOfDay();

        return $this->modelClass::dateRange($today, $endToday)
            ->with(['client', 'vehicle.make', 'vehicle.model', 'service'])
            ->orderBy('appointment_datetime', 'asc')
            ->get();
    }

    /**
     * Get upcoming appointments (next 7 days)
     *
     * @return Collection
     */
    public function getUpcoming(): Collection
    {
        $startDate = now()->startOfDay();
        $endDate = now()->addDays(7)->endOfDay();

        return $this->modelClass::dateRange($startDate, $endDate)
            ->with(['client', 'vehicle.make', 'vehicle.model', 'service'])
            ->orderBy('appointment_datetime', 'asc')
            ->get();
    }

    /**
     * Update appointment status
     *
     * @param int $appointmentId
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $appointmentId, string $status): bool
    {
        $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];

        if (!in_array($status, $validStatuses)) {
            return false;
        }

        return $this->modelClass::where('id', $appointmentId)
            ->update(['status' => $status]) > 0;
    }

    /**
     * Confirm appointment
     *
     * @param int $appointmentId
     * @return bool
     */
    public function confirm(int $appointmentId): bool
    {
        return $this->updateStatus($appointmentId, 'confirmed');
    }

    /**
     * Cancel appointment
     *
     * @param int $appointmentId
     * @return bool
     */
    public function cancel(int $appointmentId): bool
    {
        return $this->updateStatus($appointmentId, 'cancelled');
    }

    /**
     * Complete appointment
     *
     * @param int $appointmentId
     * @return bool
     */
    public function complete(int $appointmentId): bool
    {
        return $this->updateStatus($appointmentId, 'completed');
    }

    /**
     * Get appointments count by status
     *
     * @return array
     */
    public function getStatusCounts(): array
    {
        return [
            'pending' => $this->modelClass::byStatus('pending')->count(),
            'confirmed' => $this->modelClass::byStatus('confirmed')->count(),
            'cancelled' => $this->modelClass::byStatus('cancelled')->count(),
            'completed' => $this->modelClass::byStatus('completed')->count(),
        ];
    }

    /**
     * Get appointments by client ID
     *
     * @param int $clientId
     * @return Collection
     */
    public function getByClient(int $clientId): Collection
    {
        return $this->modelClass::byClient($clientId)
            ->with(['vehicle.make', 'vehicle.model', 'service'])
            ->orderBy('appointment_datetime', 'desc')
            ->get();
    }
}
