@section('title', __('panel.admins'))
@section('description', __('panel.admin_management'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_admins') }}</flux:breadcrumbs.item>
@endsection



@section('actions')
@can('administrators.create')
<x-buttons.button-module
    icon="plus"
    href="{{route('v1.panel.admins.create')}}"
    label="{{ __('panel.new_admin') }}"
    variant="primary"
/>
@endcan
@endsection

<x-containers.card-container>

        <x-table.table
        :data="$admins"
        :perPageOptions="$perPageOptions"
        :currentPerPage="$perPage"
        :search="$search"
        searchPlaceholder="{{ __('panel.search_admins_placeholder') }}"
    >

        <x-slot name="filters">
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
                sortable="true"
                sortField="name"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.name') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="email"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.email') }}
            </x-table.colum>
            <x-table.colum
                sortable="true"
                sortField="status"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.status') }}
            </x-table.colum>
            <x-table.colum
                sortable="true"
                sortField="created_at"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.created_at') }}
            </x-table.colum>
            <x-table.colum>{{ __('panel.actions') }}</x-table.colum>
        </x-slot>
        <x-slot name="rows">
            @foreach($admins as $admin)
            @php
                $timezone = session('timezone') ?? 'UTC';
                $created_at = \Carbon\Carbon::parse($admin->created_at);
                $created_at = dateToLocal($created_at, $timezone);
            @endphp
            <x-table.row>
                <x-table.cell>
                    {{$admin->name}} {{$admin->last_name}}
                </x-table.cell>
                <x-table.cell>
                    {{$admin->email}}
                </x-table.cell>
                <x-table.cell>
                    <flux:badge color="{{$admin->status == 'A' ? 'lime' : 'red'}}">
                        {{$admin->status == 'A' ? __('panel.active') : __('panel.inactive') }}
                    </flux:badge>
                </x-table.cell>
                <x-table.cell>
                    {{$created_at->format('m/d/Y H:i')}}
                </x-table.cell>
                <x-table.cell>

                    <flux:button.group>
                        @can('administrators.update')
                        <flux:button size="sm" icon="pencil" icon:variant="outline" class="cursor-pointer" href="{{ route('v1.panel.admins.edit', $admin->id) }}"></flux:button>
                        @endcan

                        @can('administrators.update-status')
                        @if($admin->status == 'A')
                        <flux:tooltip content="{{ __('panel.tooltip_deactivate') }}">
                            <flux:button size="sm" icon="hand-thumb-down" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$admin->id}}, 'I')" wire:confirm="{{ __('panel.confirm_deactivate') }} {{ __('panel.admin') }}"></flux:button>
                        </flux:tooltip>
                        @else
                        <flux:tooltip content="{{ __('panel.tooltip_activate') }}">
                            <flux:button size="sm" icon="hand-thumb-up" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$admin->id}}, 'A')" wire:confirm="{{ __('panel.confirm_activate') }} {{ __('panel.admin') }}"></flux:button>
                        </flux:tooltip>
                        @endif
                        @endcan

                    </flux:button.group>


                </x-table.cell>
            </x-table.row>
            @endforeach
        </x-slot>
    </x-table.table>

</x-containers.card-container>

