@extends('v1.layouts.mails.main')

@section('title')
    Bienvenido a Hyundai de Rexville
@endsection

@section('user_name')
    {{ $notifiable->name ?? '' }}
@endsection

@section('message')
    Te damos la bienvenida a la app The Special One, tu cuenta ha sido creada exitosamente.
@endsection

@section('content')
    Tu credenciales de acceso son:
    <br>
    Usuario: {{ $notifiable->email }}
    <br>
    Contraseña: {{ $password }}
    <br>
@endsection

@section('additional_info')
    @component('components.mail.additional-information')
        @slot('additional_info')
            .
        @endslot
    @endcomponent
@endsection
