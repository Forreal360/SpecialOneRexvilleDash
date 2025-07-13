@section('title', __('panel.admins'))
@section('description', __('panel.admin_management'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_admins') }}</flux:breadcrumbs.item>
@endsection



@section('actions')
<x-buttons.button-module
    icon="plus"
    href="{{route('v1.panel.admins.create')}}"
    label="{{ __('panel.new_admin') }}"
    variant="primary"
/>
@endsection

<x-containers.card-container>

        <x-table.table
        :data="$admins"
        :perPageOptions="$perPageOptions"
        :currentPerPage="$perPage"
        :search="$search"
        searchPlaceholder="Buscar administradores..."
    >

        <x-slot name="filters">
            {{-- Buscador --}}


            {{-- Estado --}}
            <flux:select wire:model.live="status" size="sm" placeholder="{{ __('panel.status') }}" class="w-full ">
                <flux:select.option value="A">{{ __('panel.active') }}</flux:select.option>
                <flux:select.option value="I">{{ __('panel.inactive') }}</flux:select.option>
            </flux:select>

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
                    {{$admin->created_at->format('d/m/Y H:i')}}
                </x-table.cell>
                <x-table.cell>

                    <flux:button.group>
                        <flux:button size="sm" icon="pencil" icon:variant="outline" class="cursor-pointer" href="{{ route('v1.panel.admins.edit', $admin->id) }}"></flux:button>


                        @if($admin->status == 'A')
                        <flux:tooltip content="{{ __('panel.tooltip_admin_deactivate') }}">
                            <flux:button size="sm" icon="hand-thumb-down" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$admin->id}}, 'I')" wire:confirm="{{ __('panel.confirm_admin_deactivate') }}"></flux:button>
                        </flux:tooltip>
                        @else
                        <flux:tooltip content="{{ __('panel.tooltip_admin_activate') }}">
                            <flux:button size="sm" icon="hand-thumb-up" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$admin->id}}, 'A')" wire:confirm="{{ __('panel.confirm_admin_activate') }}"></flux:button>
                        </flux:tooltip>
                        @endif

                    </flux:button.group>


                </x-table.cell>
            </x-table.row>
            @endforeach
        </x-slot>
    </x-table.table>

</x-containers.card-container>

