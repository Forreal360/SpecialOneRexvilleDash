@section('title', __('panel.client_services'))
@section('description', __('panel.client_service_management'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.clients.index')}}" separator="slash">{{ __('panel.breadcrumb_clients') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_client_services') }}</flux:breadcrumbs.item>
@endsection

@section('actions')
<x-buttons.button-module
    icon="plus"
    href="{{route('v1.panel.client-services.create', $clientId)}}"
    label="{{ __('panel.new_client_service') }}"
    variant="primary"
/>
@endsection

<x-containers.card-container>

    <x-table.table
        :data="$clientServices"
        :perPageOptions="$perPageOptions"
        :currentPerPage="$perPage"
        :search="$search"
        searchPlaceholder="{{ __('panel.search') }}"
    >

        <x-slot name="filters">
            <flux:field class="w-full">
                <flux:label>{{ __('panel.vehicle') }}</flux:label>
                <flux:select wire:model.live="vehicle_id" size="sm" placeholder="{{ __('panel.select_vehicle') }}">
                    <flux:select.option value="">{{ __('panel.all_vehicles') }}</flux:select.option>
                    @foreach($vehicles as $vehicle)
                        <flux:select.option value="{{ $vehicle->id }}">
                            {{ $vehicle->year }} {{ $vehicle->make->name }} {{ $vehicle->model->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field class="w-full">
                <flux:label>{{ __('panel.service') }}</flux:label>
                <flux:select wire:model.live="service_id" size="sm" placeholder="{{ __('panel.select_service') }}">
                    <flux:select.option value="">{{ __('panel.all_services') }}</flux:select.option>
                    @foreach($services as $service)
                        <flux:select.option value="{{ $service->id }}">{{ $service->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <x-forms.flatpickr-date
                name="date_from"
                wire:model.live="date_from"
                size="sm"
                label="{{ __('panel.date_from') }}"
                dateFormat="m/d/Y"
                placeholder="{{ __('panel.date_from') }}"
                locale="{{ app()->getLocale() }}"
            />

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
                sortField="vehicle_id"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.vehicle') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="service_id"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.service') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="date"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.service_date') }}
            </x-table.colum>

            <x-table.colum>{{ __('panel.actions') }}</x-table.colum>
        </x-slot>

        <x-slot name="rows">
            @foreach($clientServices as $clientService)
                @php
                    $timezone = session('timezone') ?? 'UTC';
                    $created_at = \Carbon\Carbon::parse($clientService->created_at);
                    $created_at = dateToLocal($created_at, $timezone);
                    $service_date = \Carbon\Carbon::parse($clientService->date);
                @endphp
                <x-table.row>

                    <x-table.cell>
                        {{ $clientService->vehicle->year }}
                        {{ $clientService->vehicle->make->name }}
                        {{ $clientService->vehicle->model->name }} <br>
                        {{ $clientService->vehicle->vin }}
                    </x-table.cell>

                    <x-table.cell>{{ $clientService->service->name }}</x-table.cell>

                    <x-table.cell>{{ $service_date->format('m/d/Y') }}</x-table.cell>

                    <x-table.cell>
                        <flux:button.group>
                            <flux:button size="sm" icon="pencil" icon:variant="outline" class="cursor-pointer" href="{{ route('v1.panel.client-services.edit', [$clientId, $clientService->id]) }}"></flux:button>
                        </flux:button.group>
                    </x-table.cell>
                </x-table.row>
            @endforeach
        </x-slot>

    </x-table.table>

</x-containers.card-container>
