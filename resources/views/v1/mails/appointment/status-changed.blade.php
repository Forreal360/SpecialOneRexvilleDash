@extends('v1.layouts.mails.main')

@section('title')
    Actualización de tu agendamiento
@endsection

@section('user_name')
    {{ $appointment->client->name ?? '' }}
@endsection

@section('message')
    Te informamos que el estado de tu agendamiento ha sido actualizado.
@endsection

@section('content')
    <div style="margin-bottom: 20px;">
        <p><strong>Detalles del agendamiento:</strong></p>

        <p><strong>Fecha y hora:</strong> {{ $appointment->getFormattedDateTimeAttribute() }}</p>

        <p><strong>Servicios:</strong> {{ $appointment->appointmentServices->where('status', 'A')->pluck('service.name')->implode(', ') }}</p>

        <p><strong>Vehículo:</strong> {{ $appointment->vehicle->make->name ?? '' }} {{ $appointment->vehicle->model->name ?? '' }} {{ $appointment->vehicle->year }}</p>

        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p><strong>Estado anterior:</strong>
                <span style="color: #6c757d;">{{ $previousStatusText }}</span>
            </p>
            <p><strong>Estado actual:</strong>
                <span style="color: {{ $newStatus === 'confirmed' ? '#28a745' : ($newStatus === 'cancelled' ? '#dc3545' : ($newStatus === 'completed' ? '#007bff' : '#ffc107')) }}; font-weight: bold;">
                    {{ $statusText }}
                </span>
            </p>
        </div>

        @if($appointment->notes)
            <p><strong>Notas:</strong> {{ $appointment->notes }}</p>
        @endif
    </div>
@endsection

@section('additional_info')
    @component('components.mail.additional-information')
        @slot('additional_info')
            @if($newStatus === 'confirmed')
                Tu agendamiento ha sido confirmado. Te esperamos en la fecha y hora programada.
            @elseif($newStatus === 'cancelled')
                Tu agendamiento ha sido cancelado. Si necesitas reagendar, puedes contactarnos.
            @elseif($newStatus === 'completed')
                ¡Gracias por visitarnos! Tu servicio ha sido completado exitosamente.
            @endif
        @endslot
    @endcomponent
@endsection
