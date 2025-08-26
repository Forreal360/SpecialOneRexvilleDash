@section('title', __('panel.vehicles'))
@section('description', __('panel.vehicle_management'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.clients.index')}}" separator="slash">{{ __('panel.breadcrumb_clients') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_vehicles') }}</flux:breadcrumbs.item>
@endsection



@section('actions')
@can('clients-vehicles.create')
<x-buttons.button-module
    icon="plus"
    href="{{route('v1.panel.vehicles.create', $clientId)}}"
    label="{{ __('panel.new_vehicle') }}"
    variant="primary"
/>
@endcan
@endsection

<x-containers.card-container>

        <x-table.table
        :data="$vehicles"
        :perPageOptions="$perPageOptions"
        :currentPerPage="$perPage"
        :search="$search"
        searchPlaceholder="{{ __('panel.search') }}"
        >

        <x-slot name="filters">
            <flux:field class="w-full">
                <flux:label>{{ __('panel.make') }}</flux:label>
                <flux:select wire:model.live="make_id" size="sm" placeholder="{{ __('panel.select_make') }}">
                    <flux:select.option value="">{{ __('panel.all_makes') }}</flux:select.option>
                    @foreach($makes as $make)
                        <flux:select.option value="{{ $make->id }}">{{ $make->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field class="w-full">
                <flux:label>{{ __('panel.model') }}</flux:label>
                <flux:select wire:model.live="model_id" size="sm" placeholder="{{ __('panel.select_model') }}" >
                    <flux:select.option value="">{{ $make_id ? __('panel.all_models') : __('panel.select_make_first') }}</flux:select.option>
                    @foreach($models as $model)
                        <flux:select.option value="{{ $model->id }}">{{ $model->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field class="w-full">
                <flux:label>{{ __('panel.year') }}</flux:label>
                <flux:input wire:model.live="year" size="sm" placeholder="{{ __('panel.year') }}" clearable/>
            </flux:field>

            <x-forms.flatpickr-date
                name="buy_date"
                wire:model.live="buy_date"
                size="sm"
                label="{{ __('panel.buy_date') }}"
                dateFormat="m/d/Y"
                placeholder="{{ __('panel.buy_date') }}"
                locale="{{ app()->getLocale() }}"
            />

            <flux:field class="w-full">
                <flux:label>{{ __('panel.vin') }}</flux:label>
                <flux:input wire:model.live="vin" size="sm" placeholder="{{ __('panel.vin') }}" clearable/>
            </flux:field>

            <flux:field class="w-full">
                <flux:label>{{ __('panel.status') }}</flux:label>
                <flux:select wire:model.live="status" size="sm" placeholder="{{ __('panel.status') }}">
                    <flux:select.option value="A">{{ __('panel.active') }}</flux:select.option>
                    <flux:select.option value="I">{{ __('panel.inactive') }}</flux:select.option>
                </flux:select>
            </flux:field>
        </x-slot>

        <x-slot name="colums">

            <x-table.colum
                sortable="false"
            >
                {{ __('panel.image') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="year"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.year') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="make"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.make') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="model"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.model') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="vin"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.vin') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="buy_date"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.buy_date') }}
            </x-table.colum>
            <x-table.colum
                sortable="true"
                sortField="insurance"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.insurance') }}
            </x-table.colum>
            <x-table.colum>{{ __('panel.actions') }}</x-table.colum>
        </x-slot>
        <x-slot name="rows">
            @foreach($vehicles as $vehicle)
            @php
                $timezone = session('timezone') ?? 'UTC';
                $created_at = \Carbon\Carbon::parse($vehicle->created_at);
                $created_at = dateToLocal($created_at, $timezone);

                $buy_date = \Carbon\Carbon::parse($vehicle->buy_date);
            @endphp
            <x-table.row>
                <x-table.cell>
                    <flux:button icon="photo" icon:variant="outline" class="cursor-pointer" href="{{$vehicle->image_path}}" target="_blank"></flux:button>
                </x-table.cell>
                <x-table.cell>
                    {{$vehicle->year}}
                </x-table.cell>
                <x-table.cell>
                    {{$vehicle->make->name}}
                </x-table.cell>
                <x-table.cell>
                    {{$vehicle->model->name}}
                </x-table.cell>
                <x-table.cell>
                    {{$vehicle->vin}}
                </x-table.cell>
                <x-table.cell>
                    {{$buy_date->format('m/d/Y')}}
                </x-table.cell>
                <x-table.cell>
                    {{$vehicle->insurance}}
                </x-table.cell>
                <x-table.cell>
                    <flux:button.group>
                        @can('clients-vehicles.update')
                        <flux:button size="sm" icon="pencil" icon:variant="outline" class="cursor-pointer" href="{{ route('v1.panel.vehicles.edit', [$clientId, $vehicle->id]) }}"></flux:button>
                        @endcan

                        @can('clients-vehicles.update-status')
                        @if($vehicle->status == 'A')
                            <flux:tooltip content="{{ __('panel.tooltip_deactivate') }}">
                                <flux:button size="sm" icon="hand-thumb-down" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$vehicle->id}}, 'I')" wire:confirm="{{ __('panel.confirm_deactivate') }} {{ __('panel.vehicle') }}"></flux:button>
                            </flux:tooltip>
                        @else
                            <flux:tooltip content="{{ __('panel.tooltip_activate') }}">
                                <flux:button size="sm" icon="hand-thumb-up" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$vehicle->id}}, 'A')" wire:confirm="{{ __('panel.confirm_activate') }} {{ __('panel.vehicle') }}"></flux:button>
                            </flux:tooltip>
                        @endif
                        @endcan

                        @can('clients-vehicles-services.get')
                        <flux:tooltip content="{{ __('panel.tooltip_vehicle_services') }}">
                            <flux:button size="sm" icon="wrench-screwdriver" icon:variant="outline" class="cursor-pointer" href="{{ route('v1.panel.client-services.index', ['clientId' => $clientId, 'vehicle_id' => $vehicle->id]) }}"></flux:button>
                        </flux:tooltip>
                        @endcan

                    </flux:button.group>
                </x-table.cell>
            </x-table.row>
            @endforeach
        </x-slot>
    </x-table.table>

</x-containers.card-container>
