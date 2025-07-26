<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Notifications\AppointmentStatusChangedNotification;

class TestAppointmentNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:appointment-notification {appointment_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test appointment status change notification email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appointmentId = $this->argument('appointment_id');

        try {
            // Find the appointment with relationships
            $appointment = Appointment::with(['client', 'vehicle.make', 'vehicle.model', 'service'])
                ->findOrFail($appointmentId);

            $this->info("Encontrado agendamiento ID: {$appointment->id}");
            $this->info("Cliente: {$appointment->client->name} ({$appointment->client->email})");
            $this->info("Estado actual: {$appointment->status}");
            $this->info("Servicio: {$appointment->service->name}");
            $this->info("Vehículo: {$appointment->vehicle->make->name} {$appointment->vehicle->model->name} {$appointment->vehicle->year}");

            // Ask for confirmation
            if (!$this->confirm('¿Enviar email de prueba de cambio de estado?')) {
                $this->info('Operación cancelada.');
                return 0;
            }

            // Send test notification (simulating a status change from pending to confirmed)
            $appointment->client->notify(
                new AppointmentStatusChangedNotification($appointment, 'pending', 'confirmed')
            );

            $this->info('✓ Notificación de email enviada exitosamente!');
            $this->info("Email enviado a: {$appointment->client->email}");

        } catch (\Exception $e) {
            $this->error('Error al enviar la notificación: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
