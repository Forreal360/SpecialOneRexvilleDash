@section('title', __('panel.vehicle_services'))
@section('description', __('panel.create_vehicle_service'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.vehicle-services.index')}}" separator="slash">{{ __('panel.breadcrumb_vehicle_services') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_create') }}</flux:breadcrumbs.item>
@endsection

@section('actions')

@endsection

<x-containers.card-container>
    <form wire:submit.prevent="createVehicleService">
        <div class="flex-1 space-y-6">

            <x-forms.form-field label="{{ __('panel.name') }}*" for="name" :error="$errors->first('name')">
                <flux:input
                    id="name"
                    wire:model="name"
                    placeholder="{{ __('panel.vehicle_service_name_placeholder') }}"
                    error="{{ $errors->first('name') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.status') }}*" for="status" :error="$errors->first('status')">
                <flux:select
                    id="status"
                    wire:model="status"
                    error="{{ $errors->first('status') }}"
                >
                    <flux:select.option value="A">{{ __('panel.active') }}</flux:select.option>
                    <flux:select.option value="I">{{ __('panel.inactive') }}</flux:select.option>
                </flux:select>
            </x-forms.form-field>

            <div class="flex justify-end space-x-3 pt-0 px-6 pb-6">
                <flux:button
                    href="{{route('v1.panel.vehicle-services.index')}}"
                    type="button"
                    variant="danger"
                >
                    {{ __('panel.cancel') }}
                </flux:button>
                <flux:button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    wire:target="createVehicleService"
                    variant="primary"
                >
                    <span wire:loading.remove wire:target="createVehicleService">{{ __('panel.create_vehicle_service') }}</span>
                    <span wire:loading wire:target="createVehicleService">{{ __('panel.loading') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</x-containers.card-container>
