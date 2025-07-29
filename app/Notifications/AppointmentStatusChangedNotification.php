<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;
    protected $previousStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment, string $previousStatus, string $newStatus)
    {
        $this->appointment = $appointment;
        $this->previousStatus = $previousStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->getStatusText($this->newStatus);
        $subject = 'Actualización de tu agendamiento - ' . $statusText;

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hola ' . $this->appointment->client->name)
            ->line('Te informamos que el estado de tu agendamiento ha cambiado.')
            ->line('**Estado anterior:** ' . $this->getStatusText($this->previousStatus))
            ->line('**Estado actual:** ' . $statusText)
            ->line('**Fecha y hora:** ' . $this->appointment->getFormattedDateTimeAttribute())
            ->line('**Servicios:** ' . $this->appointment->services->pluck('name')->implode(', '))
            ->line('**Vehículo:** ' . $this->appointment->vehicle->make->name . ' ' . $this->appointment->vehicle->model->name . ' ' . $this->appointment->vehicle->year)
            ->when($this->appointment->notes, function ($message) {
                return $message->line('**Notas:** ' . $this->appointment->notes);
            })
            ->action('Ver mis agendamientos', url('/cliente/agendamientos'))
            ->line('Si tienes alguna pregunta, no dudes en contactarnos.')
            ->view('v1.mails.appointment.status-changed', [
                'appointment' => $this->appointment,
                'previousStatus' => $this->previousStatus,
                'newStatus' => $this->newStatus,
                'statusText' => $statusText,
                'previousStatusText' => $this->getStatusText($this->previousStatus),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'previous_status' => $this->previousStatus,
            'new_status' => $this->newStatus,
            'appointment_datetime' => $this->appointment->appointment_datetime,
            'service_name' => $this->appointment->service->name,
        ];
    }

    /**
     * Get status text in Spanish
     */
    private function getStatusText(string $status): string
    {
        return match($status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmado',
            'cancelled' => 'Cancelado',
            'completed' => 'Completado',
            default => ucfirst($status),
        };
    }
}
