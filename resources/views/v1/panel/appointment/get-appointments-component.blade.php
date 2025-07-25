@section('title', __('panel.appointments'))
@section('description', __('panel.appointment_management'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_appointments') }}</flux:breadcrumbs.item>
@endsection

@section('actions')
<!-- Stats Cards -->
@endsection

<div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-700 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-zinc-200">{{ __('panel.appointment_status_pending') }}</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $statusCounts['pending'] }}</p>
                </div>
                <flux:icon.clock class="h-8 w-8 text-yellow-600" />
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-700 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-zinc-200">{{ __('panel.appointment_status_confirmed') }}</p>
                    <p class="text-2xl font-bold text-green-600">{{ $statusCounts['confirmed'] }}</p>
                </div>
                <flux:icon.check-circle class="h-8 w-8 text-green-600" />
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-700 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-zinc-200">{{ __('panel.appointment_status_cancelled') }}</p>
                    <p class="text-2xl font-bold text-red-600">{{ $statusCounts['cancelled'] }}</p>
                </div>
                <flux:icon.x-circle class="h-8 w-8 text-red-600" />
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-700 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-zinc-200">{{ __('panel.appointment_status_completed') }}</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $statusCounts['completed'] }}</p>
                </div>
                <flux:icon.check-badge class="h-8 w-8 text-blue-600" />
            </div>
        </div>
    </div>

    <x-containers.card-container>
        <x-table.table
            :data="$appointments"
            :perPageOptions="$perPageOptions"
            :currentPerPage="$perPage"
            :search="$search"
            searchPlaceholder="{{ __('panel.search_appointments_placeholder') }}"
        >
            <x-slot name="filters">
                <!-- Filtro de estado -->
                <flux:field class="w-full">
                    <flux:label>{{ __('panel.status') }}</flux:label>
                    <flux:select wire:model.live="status" size="sm" placeholder="{{ __('panel.all_statuses') }}">
                        @foreach($statusOptions as $value => $label)
                            <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>

                <!-- Filtro de fecha desde -->
                <x-forms.flatpickr-date
                    name="date_from"
                    wire:model.live="date_from"
                    size="sm"
                    label="{{ __('panel.date_from') }}"
                    dateFormat="m/d/Y"
                    placeholder="{{ __('panel.date_from') }}"
                    locale="{{ app()->getLocale() }}"
                />

                <!-- Filtro de fecha hasta -->
                <x-forms.flatpickr-date
                    name="date_to"
                    wire:model.live="date_to"
                    size="sm"
                    label="{{ __('panel.date_to') }}"
                    dateFormat="m/d/Y"
                    placeholder="{{ __('panel.date_to') }}"
                    locale="{{ app()->getLocale() }}"
                />
            </x-slot>

            <x-slot name="colums">
                <x-table.colum
                    sortable="true"
                    sortField="appointment_datetime"
                    :currentSortBy="$sortBy"
                    :currentSortDirection="$sortDirection"
                >
                    {{ __('panel.appointment_datetime') }}
                </x-table.colum>

                <x-table.colum>
                    {{ __('panel.client') }}
                </x-table.colum>

                <x-table.colum>
                    {{ __('panel.vehicle') }}
                </x-table.colum>

                <x-table.colum>
                    {{ __('panel.service') }}
                </x-table.colum>

                <x-table.colum
                    sortable="true"
                    sortField="status"
                    :currentSortBy="$sortBy"
                    :currentSortDirection="$sortDirection"
                >
                    {{ __('panel.status') }}
                </x-table.colum>

                <x-table.colum>
                    {{ __('panel.notes') }}
                </x-table.colum>

                <x-table.colum>{{ __('panel.actions') }}</x-table.colum>
            </x-slot>

            <x-slot name="rows">
                @foreach($appointments as $appointment)
                    @php
                        $timezone = session('timezone') ?? 'UTC';
                        $appointmentDateTime = \Carbon\Carbon::parse($appointment->appointment_datetime);
                        $appointmentDateTime = dateToLocal($appointmentDateTime, $timezone);
                    @endphp
                    <x-table.row>
                        <x-table.cell>
                            <div class="text-sm">
                                <div class="font-medium">{{ $appointmentDateTime->format('d/m/Y') }}</div>
                                <div class="text-gray-500">{{ $appointmentDateTime->format('H:i') }}</div>
                            </div>
                        </x-table.cell>

                        <x-table.cell>
                            <div class="text-sm">
                                <div class="font-medium">{{ $appointment->client->name }} {{ $appointment->client->last_name }}</div>
                                <div class="text-gray-500">{{ $appointment->client->email }}</div>
                            </div>
                        </x-table.cell>

                        <x-table.cell>
                            <div class="text-sm">
                                <div class="font-medium">{{ $appointment->vehicle->make->name ?? '' }} {{ $appointment->vehicle->model->name ?? '' }}</div>
                                <div class="text-gray-500">{{ $appointment->vehicle->year }}</div>
                            </div>
                        </x-table.cell>

                        <x-table.cell>
                            {{ $appointment->service->name }}
                        </x-table.cell>

                        <x-table.cell>
                            @php
                                $statusColors = [
                                    'pending' => 'yellow',
                                    'confirmed' => 'green',
                                    'cancelled' => 'red',
                                    'completed' => 'blue'
                                ];
                            @endphp
                            <flux:badge color="{{ $statusColors[$appointment->status] ?? 'gray' }}">
                                {{ __('panel.appointment_status_' . $appointment->status) }}
                            </flux:badge>
                        </x-table.cell>

                        <x-table.cell>
                            @if($appointment->notes)
                                <div class="text-sm text-gray-600 truncate max-w-32" title="{{ $appointment->notes }}">
                                    {{ $appointment->notes }}
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </x-table.cell>

                        <x-table.cell>
                            <flux:button.group>
                                <!-- Edit button - only for pending and confirmed -->
                                @if(in_array($appointment->status, ['pending', 'confirmed']))
                                    <flux:tooltip content="{{ __('panel.edit_appointment') }}">
                                        <flux:button
                                            size="sm"
                                            icon="pencil"
                                            icon:variant="outline"
                                            href="{{ route('v1.panel.appointments.edit', $appointment->id) }}"
                                        ></flux:button>
                                    </flux:tooltip>
                                @endif

                                <!-- Confirm button - only for pending -->
                                @if($appointment->status === 'pending')
                                    <flux:tooltip content="{{ __('panel.confirm_appointment') }}">
                                        <flux:button
                                            size="sm"
                                            icon="check-circle"
                                            icon:variant="outline"
                                            wire:click="confirmAppointment({{ $appointment->id }})"
                                            wire:confirm="{{ __('panel.confirm_appointment_message') }}"
                                        ></flux:button>
                                    </flux:tooltip>
                                @endif

                                <!-- Cancel button - only for pending and confirmed -->
                                @if(in_array($appointment->status, ['pending', 'confirmed']))
                                    <flux:tooltip content="{{ __('panel.cancel_appointment') }}">
                                        <flux:button
                                            size="sm"
                                            icon="x-circle"
                                            icon:variant="outline"
                                            wire:click="cancelAppointment({{ $appointment->id }})"
                                            wire:confirm="{{ __('panel.cancel_appointment_message') }}"
                                        ></flux:button>
                                    </flux:tooltip>
                                @endif

                                <!-- Complete button - only for confirmed -->
                                @if($appointment->status === 'confirmed')
                                    <flux:tooltip content="{{ __('panel.complete_appointment') }}">
                                        <flux:button
                                            size="sm"
                                            icon="check-badge"
                                            icon:variant="outline"
                                            wire:click="completeAppointment({{ $appointment->id }})"
                                            wire:confirm="{{ __('panel.complete_appointment_message') }}"
                                        ></flux:button>
                                    </flux:tooltip>
                                @endif
                            </flux:button.group>
                        </x-table.cell>
                    </x-table.row>
                @endforeach
            </x-slot>
        </x-table.table>
    </x-containers.card-container>

</div>
