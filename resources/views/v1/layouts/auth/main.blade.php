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
            <div class="flex justify-end">
                <flux:dropdown x-data="{
                    currentLocale: '{{ app()->getLocale() }}',
                    changeLanguage(locale) {
                        this.currentLocale = locale;

                        // Pequeño delay para mostrar el cambio visual
                        setTimeout(() => {
                            // Construir la nueva URL manteniendo los parámetros existentes
                            let url = new URL(window.location);
                            url.searchParams.set('lang', locale);

                            // Redirigir a la nueva URL
                            window.location.href = url.toString();
                        }, 150);
                    }
                }" align="end">
                    <flux:button variant="subtle" square class="group" aria-label="Language"
                        title="{{ __('panel.change_language') }}">
                        <flux:icon.language variant="mini" class="text-zinc-500 dark:text-white ml-1" />
                    </flux:button>
                    <flux:menu>
                        <flux:menu.item x-on:click="changeLanguage('es')"
                            :class="{ 'bg-blue-50 dark:bg-blue-900  /20': currentLocale === 'es' }"
                            class="flex items-center justify-between">
                            <span>🇪🇸 Español</span>
                            <flux:icon x-show="currentLocale === 'es'" name="check" class="w-4 h-4 text-blue-500" />
                        </flux:menu.item>
                        <flux:menu.item x-on:click="changeLanguage('en')"
                            :class="{ 'bg-blue-50 dark:bg-blue-900/20': currentLocale === 'en' }"
                            class="flex items-center justify-between">
                            <span>🇺🇸 English</span>
                            <flux:icon x-show="currentLocale === 'en'" name="check" class="w-4 h-4 text-blue-500" />
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>
            <div class="flex justify-center">
                <img src="{{ asset('assets/images/rex.png') }}" alt="Hyundai de Rexville" class="w-50 dark:hidden">
                <img src="{{ asset('assets/images/rex-white.png') }}" alt="Hyundai de Rexville" class="w-50 hidden dark:flex">

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
                {{ __('auth.welcome_platform') }}
            </div>

            <div class="flex gap-4">
                <flux:avatar src="https://fluxui.dev/img/demo/user.png" size="xl" />

                <div class="flex flex-col justify-center font-medium">
                    <div class="text-lg">{{ __('auth.president_name') }}</div>
                    <div class="text-zinc-300">{{ __('auth.president_title') }}</div>
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
