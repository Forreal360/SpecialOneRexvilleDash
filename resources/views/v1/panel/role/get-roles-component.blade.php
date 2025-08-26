@section('title', __('roles.roles'))
@section('description', __('roles.role_management'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">
    {{ __('panel.breadcrumb_home') }}
</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">
    {{ __('roles.breadcrumb_roles') }}
</flux:breadcrumbs.item>
@endsection

@section('actions')
@can('roles.create')
<x-buttons.button-module
    icon="plus"
    href="{{ route('v1.panel.roles.create') }}"
    label="{{ __('roles.create_role') }}"
    variant="primary"
/>
@endcan
@endsection

<x-containers.card-container>
    <x-table.table
        :data="$roles"
        :perPageOptions="$perPageOptions"
        :currentPerPage="$perPage"
        :search="$search"
        searchPlaceholder="{{ __('roles.search_roles') }}"
    >
        <x-slot name="filters">
            <!-- Aquí se pueden agregar filtros adicionales si es necesario -->
        </x-slot>

        <x-slot name="colums">
            <x-table.colum
                sortable="true"
                sortField="name"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >{{ __('roles.name') }}</x-table.colum>

            <x-table.colum>{{ __('roles.permissions_count') }}</x-table.colum>
            <x-table.colum>{{ __('roles.actions') }}</x-table.colum>
        </x-slot>

        <x-slot name="rows">
            @if($roles && $roles->count() > 0)
                @foreach($roles as $role)
                <x-table.row>
                    <x-table.cell>
                        <span class="font-medium">{{ $role->alias }}</span>
                    </x-table.cell>

                    <x-table.cell>
                        @if($role->permissions && $role->permissions->count() > 0)
                            <flux:badge color="blue">
                                {{ $role->permissions->count() }} {{ __('roles.permissions') }}
                            </flux:badge>
                        @else
                            <span class="text-sm text-gray-400 dark:text-gray-500">
                                {{ __('roles.no_permissions_assigned') }}
                            </span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <flux:button.group>
                            @can('roles.update')
                            <flux:button
                                size="sm"
                                icon="pencil"
                                icon:variant="outline"
                                class="cursor-pointer"
                                href="{{ route('v1.panel.roles.edit', $role->id) }}"
                            ></flux:button>
                            @endcan

                            @can('roles.update-status')
                            @if($this->canDeleteRole($role->id))
                                <flux:tooltip content="{{ __('roles.delete') }}">
                                    <flux:button
                                        size="sm"
                                        icon="trash"
                                        icon:variant="outline"
                                        class="cursor-pointer"
                                        wire:click="deleteRole({{ $role->id }})"
                                        wire:confirm="{{ __('roles.confirm_delete') }}"
                                    ></flux:button>
                                </flux:tooltip>
                            @endif
                            @endcan
                        </flux:button.group>
                    </x-table.cell>
                </x-table.row>
                @endforeach
            @else
                <x-table.row>
                    <x-table.cell colspan="3">
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                            {{ __('roles.no_roles_found') }}
                        </div>
                    </x-table.cell>
                </x-table.row>
            @endif
        </x-slot>
    </x-table.table>
</x-containers.card-container>
