@section('title', __('panel.vehicle_services'))
@section('description', __('panel.vehicle_service_management'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_vehicle_services') }}</flux:breadcrumbs.item>
@endsection

@section('actions')
@can('vehicles-services.create')
<x-buttons.button-module
    icon="plus"
    href="{{route('v1.panel.vehicle-services.create')}}"
    label="{{ __('panel.new_vehicle_service') }}"
    variant="primary"
/>
@endcan
@endsection

<x-containers.card-container>

    <x-table.table
        :data="$vehicleServices"
        :perPageOptions="$perPageOptions"
        :currentPerPage="$perPage"
        :search="$search"
        searchPlaceholder="{{ __('panel.search') }}"
    >

        <x-slot name="filters">
            <flux:field class="w-full">
                <flux:label>{{ __('panel.status') }}</flux:label>
                <flux:select wire:model.live="status" size="sm" placeholder="{{ __('panel.select_status') }}">
                    <flux:select.option value="">{{ __('panel.all_statuses') }}</flux:select.option>
                    @foreach($statusOptions as $key => $label)
                        <flux:select.option value="{{ $key }}">{{ $label }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        </x-slot>

        <x-slot name="colums">


            <x-table.colum
                sortable="true"
                sortField="name"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.name') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="status"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.status') }}
            </x-table.colum>



            <x-table.colum>{{ __('panel.actions') }}</x-table.colum>
        </x-slot>

        <x-slot name="rows">
            @foreach($vehicleServices as $vehicleService)
                <x-table.row>

                    <x-table.cell>{{ $vehicleService->name }}</x-table.cell>

                    <x-table.cell>
                        <flux:badge color="{{$vehicleService->status == 'A' ? 'lime' : 'red'}}">
                            {{$vehicleService->status == 'A' ? __('panel.active') : __('panel.inactive') }}
                        </flux:badge>
                    </x-table.cell>


                    <x-table.cell>
                        <flux:button.group>
                            <flux:button size="sm" icon="pencil" icon:variant="outline" class="cursor-pointer" href="{{ route('v1.panel.vehicle-services.edit', $vehicleService->id) }}"></flux:button>

                            @if($vehicleService->status == 'A')
                            <flux:tooltip content="{{ __('panel.tooltip_deactivate') }}">
                                <flux:button size="sm" icon="hand-thumb-down" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$vehicleService->id}}, 'I')" wire:confirm="{{ __('panel.confirm_deactivate') }} {{ __('panel.vehicle_service') }}"></flux:button>
                            </flux:tooltip>
                            @else
                            <flux:tooltip content="{{ __('panel.tooltip_activate') }}">
                                <flux:button size="sm" icon="hand-thumb-up" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$vehicleService->id}}, 'A')" wire:confirm="{{ __('panel.confirm_activate') }} {{ __('panel.vehicle_service') }}"></flux:button>
                            </flux:tooltip>
                            @endif
                        </flux:button.group>
                    </x-table.cell>
                </x-table.row>
            @endforeach
        </x-slot>

    </x-table.table>

</x-containers.card-container>
