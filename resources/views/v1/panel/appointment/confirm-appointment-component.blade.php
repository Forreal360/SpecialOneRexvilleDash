@section('title', __('panel.appointments'))
@section('description', __('panel.confirm_appointment_title'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">
    {{ __('panel.breadcrumb_home') }}
</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.appointments.index')}}" separator="slash">
    {{ __('panel.appointments') }}
</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">
    {{ __('panel.breadcrumb_confirm') }}
</flux:breadcrumbs.item>
@endsection

<x-containers.card-container>
    @if($appointment)
        <form wire:submit.prevent="confirmAppointment">
            <div class="flex-1 space-y-6">
                <!-- Información del agendamiento -->
                <div class="rounded-lg px-6">
                    <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-zinc-100">{{ __('panel.appointment_information') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">{{ __('panel.client') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-zinc-100">{{ $appointment->client->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">{{ __('panel.vehicle') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-zinc-100">
                                {{ $appointment->vehicle->make->name }} {{ $appointment->vehicle->model->name }} - {{ $appointment->vehicle->year }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">{{ __('panel.date_time') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-zinc-100">{{ $appointment->formatted_date_time }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">{{ __('panel.status') }}</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ __('panel.appointment_status_' . $appointment->status) }}
                            </span>
                        </div>
                    </div>
                    @if($appointment->notes)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">{{ __('panel.notes') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-zinc-100">{{ $appointment->notes }}</p>
                        </div>
                    @endif
                </div>

                <flux:separator />

                <!-- Selección de servicios -->
                <div class="px-6 py-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-zinc-100">{{ __('panel.select_services_to_perform') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-zinc-400 mb-4">
                        {{ __('panel.select_services_description') }}
                    </p>

                    <!-- Botones de selección masiva -->
                    <div class="flex space-x-3 mb-4">
                        <flux:button 
                            wire:click="selectAllServices" 
                            type="button" 
                            variant="ghost" 
                            size="sm"
                            icon="check"
                        >
                            {{ __('panel.select_all_services') }}
                        </flux:button>
                        <flux:button 
                            wire:click="deselectAllServices" 
                            type="button" 
                            variant="ghost" 
                            size="sm"
                            icon="x-mark"
                        >
                            {{ __('panel.deselect_all_services') }}
                        </flux:button>
                    </div>

                    <div class="space-y-3">
                        @foreach($appointment->services as $service)
                            <div class="flex items-center p-4 border border-gray-200 dark:border-zinc-600 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700">
                                <flux:checkbox
                                    wire:model.live="selectedServices.{{ $service->id }}"
                                    id="service_{{ $service->id }}"
                                    class="mr-3"
                                />
                                <label for="service_{{ $service->id }}" class="flex-1 cursor-pointer">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-zinc-100">{{ $service->name }}</h4>
                                            @if($service->description)
                                                <p class="text-sm text-gray-500 dark:text-zinc-400">{{ $service->description }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            @if($service->price)
                                                <p class="text-sm font-medium text-gray-900 dark:text-zinc-100">${{ number_format($service->price, 2) }}</p>
                                            @endif
                                            @if($service->estimated_duration)
                                                <p class="text-xs text-gray-500 dark:text-zinc-400">{{ $service->estimated_duration }} min</p>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Resumen de servicios seleccionados -->
                <div class="rounded-lg px-6">
                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-400 mb-2">{{ __('panel.summary') }}</h4>
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        {{ __('panel.selected_services') }}:
                        <span class="font-medium">
                            {{ $this->selectedServicesCount }} {{ __('panel.of') }} {{ $appointment->services->count() }}
                        </span>
                    </p>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3 pt-0 px-6 pb-6">
                    <flux:button wire:click="cancel" type="button" variant="danger">
                        {{ __('panel.cancel') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ __('panel.confirm_appointment_button') }}</span>
                        <span wire:loading>{{ __('panel.loading') }}</span>
                    </flux:button>
                </div>
            </div>
        </form>
    @endif
</x-containers.card-container>
