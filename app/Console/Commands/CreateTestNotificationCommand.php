<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AdminNotificationJob;
use App\Models\Admin;

class CreateTestNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:create-test 
                            {--admin_id= : ID del administrador (opcional)}
                            {--title= : Título de la notificación (opcional)}
                            {--message= : Mensaje de la notificación (opcional)}
                            {--action= : Acción de la notificación (opcional)}
                            {--route= : Ruta para redirección (opcional)}
                            {--force : Crear sin pedir confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear una notificación de prueba para administradores y enviarla a Firestore';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtener o seleccionar admin
        $adminId = $this->option('admin_id');
        
        if (!$adminId) {
            $admins = Admin::where('status', 'A')->get();
            
            if ($admins->isEmpty()) {
                $this->error('No hay administradores activos en el sistema.');
                return 1;
            }

            if ($admins->count() === 1) {
                $admin = $admins->first();
                $adminId = $admin->id;
                $this->info("Usando admin: {$admin->name} (ID: {$admin->id})");
            } else {
                $this->info('Administradores disponibles:');
                foreach ($admins as $admin) {
                    $this->line("  {$admin->id}: {$admin->name} ({$admin->email})");
                }
                
                $adminId = $this->ask('Ingrese el ID del administrador');
                
                if (!$admins->where('id', $adminId)->first()) {
                    $this->error('ID de administrador inválido.');
                    return 1;
                }
            }
        } else {
            $admin = Admin::find($adminId);
            if (!$admin) {
                $this->error("No se encontró el administrador con ID: {$adminId}");
                return 1;
            }
        }

        // Obtener parámetros o usar valores por defecto
        $title = $this->option('title') ?: $this->ask('Título de la notificación', 'Notificación de prueba');
        $message = $this->option('message') ?: $this->ask('Mensaje de la notificación', 'Esta es una notificación de prueba generada desde el comando.');
        $action = $this->option('action') ?: $this->choice('Tipo de acción', ['none', 'redirect'], 0);
        
        $payload = [];
        if ($action === 'redirect') {
            $route = $this->option('route') ?: $this->ask('Ruta de redirección', '/v1/panel/home');
            $payload = ['route' => $route];
        }

        // Mostrar resumen
        $this->info('');
        $this->info('📧 Creando notificación con los siguientes datos:');
        $this->line("👤 Admin ID: {$adminId}");
        $this->line("📝 Título: {$title}");
        $this->line("💬 Mensaje: {$message}");
        $this->line("🎯 Acción: {$action}");
        if (!empty($payload)) {
            $this->line("🔗 Payload: " . json_encode($payload));
        }
        $this->info('');

        if (!$this->option('force') && !$this->confirm('¿Proceder con la creación de la notificación?')) {
            $this->info('Operación cancelada.');
            return 0;
        }

        try {
            // Crear la notificación usando el Job
            AdminNotificationJob::dispatch(
                admin_id: $adminId,
                title: $title,
                message: $message,
                action: $action,
                payload: $payload,
                fcm_tokens: [], // Sin tokens FCM por defecto
                send_push: false // Sin push por defecto
            );

            $this->info('');
            $this->info('✅ ¡Notificación creada exitosamente!');
            $this->info('📱 La notificación se ha guardado en la base de datos');
            $this->info('🔥 La notificación se ha enviado a Firestore para actualización en tiempo real');
            $this->info('');
            $this->comment('💡 Puedes verificar la notificación en el panel de administración');

            return 0;

        } catch (\Exception $e) {
            $this->error('');
            $this->error('❌ Error al crear la notificación:');
            $this->error($e->getMessage());
            $this->error('');
            
            return 1;
        }
    }
}
