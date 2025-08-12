@section('title', __('panel.client_services'))
@section('description', __('panel.create_client_service'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.clients.index')}}" separator="slash">{{ __('panel.breadcrumb_clients') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.client-services.index', $clientId)}}" separator="slash">{{ __('panel.breadcrumb_client_services') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_create') }}</flux:breadcrumbs.item>
@endsection

@section('actions')

@endsection

<x-containers.card-container>
    <form wire:submit.prevent="createClientService">
        <div class="flex-1 space-y-6">

            <x-forms.form-field label="{{ __('panel.vehicle') }}*" for="vehicle_id" :error="$errors->first('vehicle_id')">
                <flux:select
                    id="vehicle_id"
                    wire:model="vehicle_id"
                    error="{{ $errors->first('vehicle_id') }}"
                >
                    <flux:select.option value="">{{ __('panel.select_vehicle') }}</flux:select.option>
                    @foreach($vehicles as $vehicle)
                        <flux:select.option value="{{ $vehicle->id }}">
                            {{ $vehicle->year }} {{ $vehicle->make->name }} {{ $vehicle->model->name }} - {{ $vehicle->vin }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.service') }}*" for="service_id" :error="$errors->first('service_id')">
                <flux:select
                    id="service_id"
                    wire:model="service_id"
                    error="{{ $errors->first('service_id') }}"
                >
                    <flux:select.option value="">{{ __('panel.select_service') }}</flux:select.option>
                    @foreach($services as $service)
                        <flux:select.option value="{{ $service->id }}">{{ $service->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.service_date') }}*" for="date" :error="$errors->first('date')">
                <x-forms.flatpickr-date
                    name="date"
                    wire:model="date"
                    dateFormat="m/d/Y"
                    maxDate="today"
                    placeholder="{{ __('panel.service_date') }}"
                    error="{{ $errors->first('date') }}"
                    required
                />
            </x-forms.form-field>

            <div class="flex justify-end space-x-3 pt-0 px-6 pb-6">
                <flux:button
                    href="{{route('v1.panel.client-services.index', $clientId)}}"
                    type="button"
                    variant="danger"
                >
                    {{ __('panel.cancel') }}
                </flux:button>
                <flux:button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    wire:target="createClientService"
                    variant="primary"
                >
                    <span wire:loading.remove wire:target="createClientService">{{ __('panel.create_client_service') }}</span>
                    <span wire:loading wire:target="createClientService">{{ __('panel.loading') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</x-containers.card-container>
