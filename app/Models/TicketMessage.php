<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'fromeable_type',
        'fromeable_id',
        'message',
        'message_type',
        'file_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el ticket
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Relación polimórfica con el remitente (Admin o Client)
     */
    public function fromeable()
    {
        return $this->morphTo();
    }

    /**
     * Verificar si el mensaje es de un admin
     */
    public function isFromAdmin()
    {
        return $this->fromeable_type === 'admin';
    }

    /**
     * Verificar si el mensaje es de un cliente
     */
    public function isFromClient()
    {
        return $this->fromeable_type === 'client';
    }

    /**
     * Obtener el nombre del remitente
     */
    public function getSenderNameAttribute()
    {
        if ($this->fromeable) {
            return $this->fromeable->name . ' ' . ($this->fromeable->last_name ?? '');
        }
        return 'Usuario desconocido';
    }

    /**
     * Verificar si es mensaje de texto
     */
    public function isTextMessage()
    {
        return $this->message_type === 'text';
    }
}
