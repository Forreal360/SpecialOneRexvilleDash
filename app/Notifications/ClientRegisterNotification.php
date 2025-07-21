<?php

namespace App\Notifications;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientRegisterNotification extends Notification
{
    use Queueable;

    protected $client;
    protected $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(Client $client, string $password)
    {
        $this->client = $client;
        $this->password = $password;
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
        return (new MailMessage)
            ->subject('Bienvenido a ' . config('app.name'))
            ->greeting('Hola ' . $this->client->name)
            ->line('Gracias por registrarte en ' . config('app.name') . '.')
            ->line('Tu contraseña es: ' . $this->password)
            ->line('Puedes iniciar sesión en ' . config('app.url') . ' con tu correo electrónico y contraseña.')
            ->line('Si tienes alguna pregunta, no dudes en contactarnos.')
            ->view('v1.mails.client.register-mail', [
                'notifiable' => $this->client,
                'password' => $this->password,
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
            //
        ];
    }
}
