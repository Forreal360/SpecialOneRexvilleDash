@extends('v1.layouts.panel.main')

@section('title', 'Test de Componentes')
@section('description', 'Página de pruebas para componentes')

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">Inicio</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">Test</flux:breadcrumbs.item>
@endsection

@section('actions')
<flux:button
    href="{{ route('v1.panel.home') }}"
    variant="outline"
    size="sm"
    icon="arrow-left"
>
    Volver
</flux:button>
@endsection

<x-containers.card-container>
    <div class="space-y-8 p-6">
        <div>
            <h2 class="text-2xl font-bold mb-4">Ejemplos de Componente Flatpickr</h2>
            <p class="text-gray-600 mb-6">Diferentes formatos y configuraciones disponibles</p>
        </div>

        {{-- Formato Español (por defecto) --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">1. Formato Español (DD/MM/YYYY)</h3>
            <x-forms.flatpickr-date
                name="fecha_espanol"
                label="Fecha en formato español"
                dateFormat="d/m/Y"
                placeholder="15/01/2024"
            />
        </div>

        {{-- Formato ISO --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">2. Formato ISO (YYYY-MM-DD)</h3>
            <x-forms.flatpickr-date
                name="fecha_iso"
                label="Fecha en formato ISO"
                dateFormat="Y-m-d"
                placeholder="2024-01-15"
            />
        </div>

        {{-- Formato Americano --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">3. Formato Americano (MM/DD/YYYY)</h3>
            <x-forms.flatpickr-date
                name="fecha_americano"
                label="Fecha en formato americano"
                dateFormat="m/d/Y"
                placeholder="01/15/2024"
            />
        </div>

        {{-- Formato con guiones --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">4. Formato con guiones (DD-MM-YYYY)</h3>
            <x-forms.flatpickr-date
                name="fecha_guiones"
                label="Fecha con guiones"
                dateFormat="d-m-Y"
                placeholder="15-01-2024"
            />
        </div>

        {{-- Formato con puntos --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">5. Formato con puntos (DD.MM.YYYY)</h3>
            <x-forms.flatpickr-date
                name="fecha_puntos"
                label="Fecha con puntos"
                dateFormat="d.m.Y"
                placeholder="15.01.2024"
            />
        </div>

        {{-- Formato sin ceros --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">6. Formato sin ceros (D/M/YYYY)</h3>
            <x-forms.flatpickr-date
                name="fecha_sin_ceros"
                label="Fecha sin ceros"
                dateFormat="j/n/Y"
                placeholder="15/1/2024"
            />
        </div>

        {{-- Formato largo --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">7. Formato largo (Monday, 15 January 2024)</h3>
            <x-forms.flatpickr-date
                name="fecha_largo"
                label="Fecha en formato largo"
                dateFormat="l, j F Y"
                altFormat="d/m/Y"
                altInput="true"
                placeholder="Monday, 15 January 2024"
            />
        </div>

        {{-- Formato corto --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">8. Formato corto (15 January 2024)</h3>
            <x-forms.flatpickr-date
                name="fecha_corto"
                label="Fecha en formato corto"
                dateFormat="j F Y"
                altFormat="d/m/Y"
                altInput="true"
                placeholder="15 January 2024"
            />
        </div>

        {{-- Con restricciones de fecha --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">9. Con restricciones de fecha</h3>
            <x-forms.flatpickr-date
                name="fecha_restricciones"
                label="Fecha con restricciones (hoy hasta fin de año)"
                dateFormat="d/m/Y"
                minDate="today"
                maxDate="2024-12-31"
                placeholder="Selecciona una fecha"
            />
        </div>

        {{-- Con tema oscuro --}}
        <div class="border rounded-lg p-4 bg-gray-800 text-white">
            <h3 class="text-lg font-semibold mb-2">10. Con tema oscuro</h3>
            <x-forms.flatpickr-date
                name="fecha_oscuro"
                label="Fecha con tema oscuro"
                dateFormat="d/m/Y"
                theme="dark"
                placeholder="Selecciona una fecha"
            />
        </div>

        {{-- Con números de semana --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">11. Con números de semana</h3>
            <x-forms.flatpickr-date
                name="fecha_semanas"
                label="Fecha con números de semana"
                dateFormat="d/m/Y"
                weekNumbers
                firstDayOfWeek="1"
                placeholder="Selecciona una fecha"
            />
        </div>

        {{-- Diferentes tamaños --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">12. Diferentes tamaños</h3>
            <div class="space-y-4">
                <x-forms.flatpickr-date
                    name="fecha_xs"
                    label="Tamaño XS (32px)"
                    dateFormat="d/m/Y"
                    size="xs"
                    placeholder="XS - Compacto"
                />
                <x-forms.flatpickr-date
                    name="fecha_sm"
                    label="Tamaño SM (40px)"
                    dateFormat="d/m/Y"
                    size="sm"
                    placeholder="SM - Pequeño"
                />
                <x-forms.flatpickr-date
                    name="fecha_md"
                    label="Tamaño MD (48px)"
                    dateFormat="d/m/Y"
                    size="md"
                    placeholder="MD - Estándar"
                />
                <x-forms.flatpickr-date
                    name="fecha_lg"
                    label="Tamaño LG (56px) - Por defecto"
                    dateFormat="d/m/Y"
                    size="lg"
                    placeholder="LG - Principal"
                />
                <x-forms.flatpickr-date
                    name="fecha_xl"
                    label="Tamaño XL (64px)"
                    dateFormat="d/m/Y"
                    size="xl"
                    placeholder="XL - Destacado"
                />
            </div>
        </div>

        {{-- Con Livewire --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">13. Con Livewire (ejemplo)</h3>
            <x-forms.flatpickr-date
                name="fecha_livewire"
                label="Fecha con Livewire"
                dateFormat="d/m/Y"
                wire:model="fecha_ejemplo"
                placeholder="Selecciona una fecha"
            />
            <p class="text-sm text-gray-500 mt-2">Valor en Livewire: {{ $fecha_ejemplo ?? 'No seleccionado' }}</p>
        </div>

        {{-- Con validación de errores --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">14. Con validación de errores</h3>
            <x-forms.flatpickr-date
                name="fecha_error"
                label="Fecha con error"
                dateFormat="d/m/Y"
                error="Este campo es requerido"
                placeholder="Selecciona una fecha"
            />
        </div>

        {{-- Deshabilitado --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">15. Deshabilitado</h3>
            <x-forms.flatpickr-date
                name="fecha_disabled"
                label="Fecha deshabilitada"
                dateFormat="d/m/Y"
                disabled
                placeholder="No disponible"
            />
        </div>

        {{-- Comparación altInput --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">16. Comparación: Con y sin altInput</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium mb-2">Sin altInput (un solo input):</h4>
                    <x-forms.flatpickr-date
                        name="fecha_simple"
                        label="Fecha simple"
                        dateFormat="d/m/Y"
                        placeholder="15/01/2024"
                    />
                </div>
                <div>
                    <h4 class="font-medium mb-2">Con altInput (dos inputs):</h4>
                    <x-forms.flatpickr-date
                        name="fecha_alt"
                        label="Fecha con altInput"
                        dateFormat="l, j F Y"
                        altFormat="d/m/Y"
                        altInput="true"
                        placeholder="Monday, 15 January 2024"
                    />
                </div>
            </div>
        </div>

        {{-- Botones de control --}}
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-2">17. Botones de control</h3>
            <x-forms.flatpickr-date
                name="fecha_control"
                label="Fecha con controles"
                dateFormat="d/m/Y"
                placeholder="Selecciona una fecha"
            />
            <div class="flex gap-2 mt-4">
                <flux:button
                    type="button"
                    size="sm"
                    variant="outline"
                    onclick="window.setFlatpickrDate('fecha_control', '{{ date('Y-m-d') }}')"
                >
                    Establecer hoy
                </flux:button>
                <flux:button
                    type="button"
                    size="sm"
                    variant="outline"
                    onclick="window.setFlatpickrDate('fecha_control', '{{ date('Y-m-d', strtotime('+7 days')) }}')"
                >
                    +7 días
                </flux:button>
                <flux:button
                    type="button"
                    size="sm"
                    variant="outline"
                    onclick="window.clearFlatpickrDate('fecha_control')"
                >
                    Limpiar
                </flux:button>
            </div>
        </div>
    </div>
</x-containers.card-container>
