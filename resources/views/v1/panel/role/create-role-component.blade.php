@section('title', __('roles.roles'))
@section('description', __('roles.create_role'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">
    {{ __('panel.breadcrumb_home') }}
</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.roles.index')}}" separator="slash">
    {{ __('roles.breadcrumb_roles') }}
</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">
    {{ __('roles.breadcrumb_create') }}
</flux:breadcrumbs.item>
@endsection


<x-containers.card-container>
    <form wire:submit.prevent="createRole">
        <div class="flex-1 space-y-6">
            <!-- Información básica del rol -->
            <x-forms.form-field label="{{ __('roles.role_alias') }}*" for="alias">
                <flux:input
                    wire:model="alias"
                    placeholder="{{ __('roles.role_alias_placeholder') }}"
                    required
                />
            </x-forms.form-field>


            <!-- Selección de permisos -->
            <div class="px-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('roles.select_permissions') }}</h3>
                    <div class="flex space-x-2">
                        <flux:button
                            type="button"
                            size="sm"
                            variant="primary"
                            wire:click="selectAllPermissions"
                        >
                            {{ __('roles.select_all_permissions_global') }}
                        </flux:button>
                        <flux:button
                            type="button"
                            size="sm"
                            variant="outline"
                            wire:click="deselectAllPermissions"
                        >
                            {{ __('roles.deselect_all_permissions_global') }}
                        </flux:button>
                    </div>
                </div>

                @if($permissionsByModule && count($permissionsByModule) > 0)
                    <div class="space-y-6">
                        @foreach($permissionsByModule as $module => $permissions)
                            <div class="bg-gray-50 rounded-lg p-4 dark:bg-gray-700">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 capitalize">
                                        {{ ucfirst(str_replace('-', ' ', $module)) }}
                                    </h4>
                                    <div class="flex space-x-2">
                                        <flux:button
                                            type="button"
                                            size="sm"
                                            variant="outline"
                                            wire:click="selectAllModulePermissions('{{ $module }}')"
                                        >
                                            {{ __('roles.select_all_permissions') }}
                                        </flux:button>
                                        <flux:button
                                            type="button"
                                            size="sm"
                                            variant="outline"
                                            wire:click="deselectAllModulePermissions('{{ $module }}')"
                                        >
                                            {{ __('roles.deselect_all_permissions') }}
                                        </flux:button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                                    @foreach($permissions as $permission)
                                        <div class="flex items-center">
                                            <flux:checkbox
                                                wire:key="permission-{{ $permission->id }}"
                                                wire:model="selectedPermissions"
                                                value="{{ $permission->id }}"
                                            />
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('roles.permission_aliases.' . $permission->alias) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Resumen de permisos seleccionados -->
                    @if(count($selectedPermissions) > 0)
                        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                <strong>{{ count($selectedPermissions) }}</strong> {{ __('roles.permissions') }} seleccionados
                            </p>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500 dark:text-gray-400">{{ __('roles.no_permissions_available') }}</p>
                @endif
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-3 pt-0 px-6 pb-6">
                <flux:button href="{{route('v1.panel.roles.index')}}" type="button" variant="outline">
                    {{ __('roles.cancel') }}
                </flux:button>
                <flux:button type="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('roles.create_role') }}</span>
                    <span wire:loading>{{ __('roles.loading') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</x-containers.card-container>
