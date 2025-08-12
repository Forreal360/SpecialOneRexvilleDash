@section('title', __('panel.clients'))
@section('description', __('panel.update_client'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.clients.index')}}" separator="slash">{{ __('panel.breadcrumb_clients') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_update') }}</flux:breadcrumbs.item>
@endsection

@section('actions')

@endsection

<x-containers.card-container>
    <form wire:submit.prevent="updateClient">
        <div class="flex-1 space-y-6">

            <x-forms.form-field label="{{ __('panel.name') }}*" for="name" :error="$errors->first('name')">
                <flux:input
                    id="name"
                    wire:model="name"
                    placeholder="Juan"
                    error="{{ $errors->first('name') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.last_name') }}*" for="last_name" :error="$errors->first('last_name')">
                <flux:input
                    id="last_name"
                    wire:model="last_name"
                    placeholder="Pérez"
                    error="{{ $errors->first('last_name') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.email') }}*" for="email" :error="$errors->first('email')">
                <flux:input
                    id="email"
                    wire:model="email"
                    type="email"
                    placeholder="cliente@ejemplo.com"
                    error="{{ $errors->first('email') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.license_number') }}*" for="license_number" :error="$errors->first('license_number')">
                <flux:input
                    id="license_number"
                    wire:model="license_number"
                    placeholder="ABC123456"
                    error="{{ $errors->first('license_number') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.phone') }}*" for="phone" :error="$errors->first('phone')">

                <flux:input.group>
                    <flux:select class="max-w-fit" id="phone_code"
                    wire:model.live="phone_code">
                        <flux:select.option value="1">+1</flux:select.option>
                        <flux:select.option value="52">+52</flux:select.option>
                        <flux:select.option value="58">+58</flux:select.option>
                    </flux:select>
                    <flux:input
                        id="phone"
                        wire:model.live="phone"
                        placeholder="(555)-123-4567"
                        mask="(999) 999-9999"
                        error="{{ $errors->first('phone') }}"  />
                </flux:input.group>

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
                    href="{{route('v1.panel.clients.index')}}"
                    type="button"
                    variant="danger"
                >
                    {{ __('panel.cancel') }}
                </flux:button>
                <flux:button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    wire:target="updateClient"
                    variant="primary"
                >
                    <span wire:loading.remove wire:target="updateClient">{{ __('panel.update_client') }}</span>
                    <span wire:loading wire:target="updateClient">{{ __('panel.loading') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</x-containers.card-container>
