@section('title', __('panel.appointments'))
@section('description', __('panel.update_appointment'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.appointments.index')}}" separator="slash">{{ __('panel.breadcrumb_appointments') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_update') }}</flux:breadcrumbs.item>
@endsection

@section('actions')

@endsection

<x-containers.card-container>
    <form wire:submit.prevent="updateAppointment">
        <div class="flex-1 space-y-6">

            <!-- Appointment Information Card -->
            <div class="rounded-lg px-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('panel.appointment_information') }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Client Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('panel.client_information') }}</h4>
                        <div class="space-y-1 text-sm">
                            <div><span class="font-medium">{{ __('panel.name') }}:</span> {{ $client_name }}</div>
                            <div><span class="font-medium">{{ __('panel.email') }}:</span> {{ $client_email }}</div>
                        </div>
                    </div>

                    <!-- Vehicle Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('panel.vehicle_information') }}</h4>
                        <div class="space-y-1 text-sm">
                            <div><span class="font-medium">{{ __('panel.vehicle') }}:</span> {{ $vehicle_info }}</div>
                        </div>
                    </div>

                    <!-- Service Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('panel.service_information') }}</h4>
                        <div class="space-y-1 text-sm">
                            <div><span class="font-medium">{{ __('panel.service') }}:</span> {{ $service_name }}</div>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('panel.current_status') }}</h4>
                        <div class="space-y-1 text-sm">
                            @php
                                $statusColors = [
                                    'pending' => 'yellow',
                                    'confirmed' => 'green',
                                    'cancelled' => 'red',
                                    'completed' => 'blue'
                                ];
                            @endphp
                            <flux:badge color="{{ $statusColors[$current_status] ?? 'gray' }}">
                                {{ __('panel.appointment_status_' . $current_status) }}
                            </flux:badge>
                        </div>
                    </div>
                </div>
            </div>

            <flux:separator />


            <!-- Editable Fields -->
            <x-forms.form-field
                label="{{ __('panel.appointment_date') }}*"
                for="appointment_date"
                :error="$errors->first('appointment_date')"
            >
                <x-forms.flatpickr-date
                    name="appointment_date"
                    wire:model="appointment_date"
                    dateFormat="Y-m-d"
                    placeholder="{{ __('panel.appointment_date') }}"
                    minDate="today"
                    error="{{ $errors->first('appointment_date') }}"
                    required
                />
            </x-forms.form-field>

            <x-forms.form-field
                label="{{ __('panel.appointment_time') }}*"
                for="appointment_time"
                :error="$errors->first('appointment_time')"
            >
                <flux:input
                    id="appointment_time"
                    type="time"
                    wire:model="appointment_time"
                    error="{{ $errors->first('appointment_time') }}"
                />
                @error('appointment_datetime')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </x-forms.form-field>

            <x-forms.form-field
                label="{{ __('panel.timezone') }}"
                for="timezone"
                :error="$errors->first('timezone')"
            >
                <flux:input
                    id="timezone"
                    wire:model="timezone"
                    placeholder="UTC"
                    disabled
                    error="{{ $errors->first('timezone') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field
                label="{{ __('panel.notes') }}"
                for="notes"
                :error="$errors->first('notes')"
            >
                <flux:textarea
                    id="notes"
                    wire:model="notes"
                    placeholder="{{ __('panel.appointment_notes_placeholder') }}"
                    rows="4"
                    error="{{ $errors->first('notes') }}"
                />
            </x-forms.form-field>

            <div class="flex justify-end space-x-3 pt-0 px-6 pb-6">
                <flux:button
                    href="{{ route('v1.panel.appointments.index') }}"
                    type="button"
                    variant="danger"
                >
                    {{ __('panel.cancel') }}
                </flux:button>
                <flux:button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    wire:target="updateAppointment"
                    variant="primary"
                >
                    <span wire:loading.remove wire:target="updateAppointment">{{ __('panel.update_appointment') }}</span>
                    <span wire:loading wire:target="updateAppointment">{{ __('panel.updating') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</x-containers.card-container>
