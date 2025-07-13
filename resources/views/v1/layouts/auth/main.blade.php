<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Hyundai de Rexville')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @vite('resources/css/app.css')
    @fluxAppearance

    {{-- Livewire styles --}}
    @livewireStyles

    {{-- CSS extra por stack --}}
    @stack('styles')
    {{-- CSS extra por sección --}}
    @yield('styles')
</head>
<body class="flex min-h-screen bg-white dark:bg-zinc-800">

    <div class="flex-1 flex justify-center items-center">
        <div class="w-80 max-w-80 space-y-6">
            <div class="flex justify-center">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Hyundai de Rexville" class="w-50 ">
            </div>
            <div class="flex justify-center opacity-50">
                <a href="/" class="group flex items-center gap-3">
                    <span class="text-xl font-semibold text-zinc-800 dark:text-white">Hyundai de Rexville</span>
                </a>
            </div>

            {{ $slot ?? ''}}


        </div>
    </div>

    <div class="flex-1 p-4 max-lg:hidden">
        <div class="text-white relative rounded-lg h-full w-full bg-zinc-900 flex flex-col items-start justify-end p-16" style="background-image: url('/assets/images/login-background.jpg'); background-size: cover">
            <div class="flex gap-2 mb-4">
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
            </div>

            <div class="mb-6 italic font-base text-3xl xl:text-4xl">
                Bienvenido a la plataforma de Hyundai de Rexville
            </div>

            <div class="flex gap-4">
                <flux:avatar src="https://fluxui.dev/img/demo/user.png" size="xl" />

                <div class="flex flex-col justify-center font-medium">
                    <div class="text-lg">Romina Navas</div>
                    <div class="text-zinc-300">Presidente de Hyundai de Rexville</div>
                </div>
            </div>
        </div>
    </div>



    @fluxScripts

    {{-- Livewire scripts --}}
    @livewireScripts

    {{-- Scripts extra por stack --}}
    @stack('scripts')
    {{-- Scripts extra por sección --}}
    @yield('scripts')
</body>
</html>
