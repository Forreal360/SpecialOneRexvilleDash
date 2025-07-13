<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <title>Hyundai de Rexville</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @vite('resources/css/app.css')
    @fluxAppearance

    {{-- Livewire styles --}}
    @livewireStyles

    {{-- CSS extra por stack --}}
    @stack('styles')
    {{-- CSS extra por sección --}}
    @yield('styles')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
    @include('v1.layouts.panel.partials.sidebar')
    @include('v1.layouts.panel.partials.header')
    <flux:main>
        <flux:heading size="xl" level="1" class="text-accent dark:text-zinc-200 mb-2">
            <flux:breadcrumbs class="text-accent dark:text-zinc-200 lg:hidden">
                @yield('breadcrumbs')
            </flux:breadcrumbs>

            <div class="flex justify-between items-center mb-6">
                <div class="text-accent dark:text-zinc-200">@yield('title')</div>
                <flux:breadcrumbs class="text-accent dark:text-zinc-200 hidden lg:inline-flex">
                    @yield('breadcrumbs')
                </flux:breadcrumbs>
            </div>
        </flux:heading>

        {{ $slot ?? ''}}

    </flux:main>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @fluxScripts

    {{-- Livewire scripts --}}
    @livewireScripts

    {{-- Scripts extra por stack --}}
    @stack('scripts')
    {{-- Scripts extra por sección --}}
    @yield('scripts')
<script>
    @if(session('success'))
        Toastify({
            text: "Operación completada exitosamente",
            duration: 3000,
            close: false,
            gravity: "bottom",
            position: "right",
            stopOnFocus: true,
            style: {
                background: "rgb(34 197 94)",
                color: "white",
                borderRadius: "8px",
                padding: "12px 16px",
                fontSize: "14px",
                fontWeight: "500",
                boxShadow: "0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -1px rgb(0 0 0 / 0.06)",
            }
        }).showToast();
    @endif
</script>
</body>
</html>
