@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'placeholder' => 'Selecciona una fecha',
    'dateFormat' => 'd/m/Y',
    'altFormat' => null,
    'defaultDate' => null,
    'minDate' => null,
    'maxDate' => null,
    'disabled' => false,
    'required' => false,
    'class' => '',
    'error' => null,
    'size' => 'lg',
    'locale' => 'es',
    'allowInput' => true,
    'altInput' => false,
    'disableMobile' => true,
    'weekNumbers' => false,
    'firstDayOfWeek' => 1,
    'position' => 'auto',
    'theme' => 'light'
])

@php
    $id = $id ?? $name ?? 'flatpickr_' . uniqid();
    $altFormat = $altFormat ?? $dateFormat;

    // Generar clases CSS basadas en el tamaño
    $sizeClasses = [
        'xs' => 'text-xs px-3 py-2',
        'sm' => 'text-sm px-4 py-2.5',
        'md' => 'text-sm px-4 py-3',
        'lg' => 'text-base px-4 py-3',
        'xl' => 'text-lg px-4 py-3'
    ];

@endphp

<div class="w-full" wire:ignore>
    @if($label)
        <flux:label for="{{ $id }}" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-3">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </flux:label>
    @endif

    <flux:input
        type="text"
        clearable
        id="{{ $id }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        class="{{ $class }}"
        size="{{ $size }}"
        {{ $attributes }}
        data-flatpickr-config="{{ json_encode([
            'dateFormat' => $dateFormat,
            'altFormat' => $altFormat,
            'defaultDate' => $defaultDate,
            'minDate' => $minDate,
            'maxDate' => $maxDate,
            'locale' => $locale,
            'allowInput' => $allowInput,
            'altInput' => $altInput,
            'disableMobile' => $disableMobile,
            'weekNumbers' => $weekNumbers,
            'firstDayOfWeek' => $firstDayOfWeek,
            'position' => $position,
            'theme' => $theme
        ]) }}"
    />

    @if($error)
        <flux:error name="{{ $name ?? $id }}" class="mt-1 text-sm text-red-600 dark:text-red-400">
            {{ $error }}
        </flux:error>
    @endif
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @if($theme === 'dark')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    @endif
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/{{ $locale }}.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flatpickrInputs = document.querySelectorAll('[data-flatpickr-config]');

            flatpickrInputs.forEach(function(input) {
                const config = JSON.parse(input.getAttribute('data-flatpickr-config'));

                const defaultConfig = {
                    onChange: function(selectedDates, dateStr, instance) {
                        // Actualizar el valor del input
                        input.value = dateStr;

                        // Disparar evento para Livewire
                        input.dispatchEvent(new Event('input', { bubbles: true }));

                        // Callback personalizado si existe
                        if (typeof window.flatpickrCallbacks !== 'undefined' && window.flatpickrCallbacks.onChange) {
                            window.flatpickrCallbacks.onChange(selectedDates, dateStr, instance);
                        }
                    }
                };

                const finalConfig = { ...defaultConfig, ...config };
                flatpickr(input, finalConfig);
            });
        });

        // Funciones globales
        window.getFlatpickrInstance = function(inputId) {
            const input = document.getElementById(inputId);
            return input && input._flatpickr ? input._flatpickr : null;
        };

        window.clearFlatpickrDate = function(inputId) {
            const instance = window.getFlatpickrInstance(inputId);
            if (instance) {
                instance.clear();
            }
        };

        window.setFlatpickrDate = function(inputId, date) {
            const instance = window.getFlatpickrInstance(inputId);
            if (instance) {
                instance.setDate(date);
            }
        };
    </script>
@endpush
