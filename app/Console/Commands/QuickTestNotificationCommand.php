<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AdminNotificationJob;
use App\Services\V1\FirestoreService;


class QuickTestNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía una notificación de prueba rápida al admin ID 1 con datos aleatorios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $firestoreService = new FirestoreService();
        $titles = [
            'Nueva orden recibida',
            'Sistema actualizado',
            'Cliente registrado',
            'Pago procesado',
            'Reporte generado',
            'Backup completado',
            'Nueva venta',
            'Mensaje importante',
            'Tarea pendiente',
            'Promoción activada'
        ];

        $messages = [
            'Se ha procesado una nueva solicitud en el sistema.',
            'La operación se completó exitosamente.',
            'Hay elementos que requieren tu atención.',
            'El proceso automático finalizó correctamente.',
            'Se detectó una actividad importante.',
            'Revisa los detalles en el panel de control.',
            'La acción solicitada fue ejecutada.',
            'Tienes nuevas actualizaciones disponibles.',
            'El sistema registró un evento relevante.',
            'Se generó un nuevo elemento para revisión.'
        ];

        $actions = ['none', 'redirect'];
        $routes = [
            '/v1/panel/home',
            '/v1/panel/admins',
            '/v1/panel/clients',
            '/v1/panel/reports',
            '/v1/panel/settings'
        ];

        // Generar datos aleatorios
        $title = $titles[array_rand($titles)];
        $message = $messages[array_rand($messages)];
        $action = $actions[array_rand($actions)];
        $payload = $action === 'redirect' ? ['route' => $routes[array_rand($routes)]] : [];

        try {
            // Crear la notificación
            AdminNotificationJob::dispatch(
                admin_id: 1,
                title: $title,
                message: $message,
                action: $action,
                payload: $payload,
                fcm_tokens: [],
                send_push: false
            );

            $this->info('🔥 Notificación enviada!');
            $this->line("📝 {$title}");
            $this->line("💬 {$message}");
            if ($action === 'redirect') {
                $this->line("🔗 {$payload['route']}");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
