@section('title', __('panel.admins'))
@section('description', __('panel.update_admin'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.admins.index')}}" separator="slash">{{ __('panel.breadcrumb_admins') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_update') }}</flux:breadcrumbs.item>
@endsection

@section('actions')

@endsection

<x-containers.card-container>
    <form wire:submit.prevent="updateAdmin">
        <div class="flex-1 space-y-6">
            <x-forms.form-field label="{{ __('panel.name') }}*" for="name" :error="$errors->first('name')">
                <flux:input
                    id="name"
                    wire:model="name"
                    placeholder="Juan"
                    error="{{ $errors->first('form.name') }}"
                />
            </x-forms.form-field>
            <x-forms.form-field label="{{ __('panel.last_name') }}*" for="last_name" :error="$errors->first('last_name')">
                <flux:input
                    id="last_name"
                    wire:model="last_name"
                    placeholder="Pérez"
                    error="{{ $errors->first('form.last_name') }}"
                />
            </x-forms.form-field>
            <x-forms.form-field label="{{ __('panel.email') }}*" for="email" :error="$errors->first('email')">
                <flux:input
                    id="email"
                    wire:model="email"
                    type="email"
                    placeholder="admin@ejemplo.com"
                    error="{{ $errors->first('form.email') }}"
                />
            </x-forms.form-field>
            <x-forms.form-field label="{{ __('panel.password') }} ({{ __('panel.leave_blank_to_keep_current') }})" for="password" :error="$errors->first('password')">
                <flux:input
                    id="password"
                    wire:model="password"
                    type="password"
                    placeholder="••••••••"
                    error="{{ $errors->first('form.password') }}"
                />
            </x-forms.form-field>
            <x-forms.form-field label="{{ __('panel.password_confirmation') }}" for="password_confirmation" :error="$errors->first('password_confirmation')">
                <flux:input
                    id="password_confirmation"
                    wire:model="password_confirmation"
                    type="password"
                    placeholder="••••••••"
                    error="{{ $errors->first('form.password_confirmation') }}"
                />
            </x-forms.form-field>
            <x-forms.form-field label="{{ __('panel.status') }}" for="status" :error="$errors->first('status')">
                <flux:select wire:model="status" size="sm" placeholder="{{ __('panel.status') }}" class="w-full">
                    <flux:select.option value="">{{ __('panel.select_option') }}</flux:select.option>
                    <flux:select.option value="A">{{ __('panel.active') }}</flux:select.option>
                    <flux:select.option value="I">{{ __('panel.inactive') }}</flux:select.option>
                </flux:select>
            </x-forms.form-field>

            <div class="flex justify-end space-x-3 pt-0 px-6 pb-6">
                <flux:button
                    href="{{route('v1.panel.admins.index')}}"
                    type="button"
                    wire:click="cancel"
                    variant="danger"
                >
                    {{ __('panel.cancel') }}
                </flux:button>
                <flux:button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    variant="primary"
                >
                    <span wire:loading.remove>{{ __('panel.update_admin') }}</span>
                    <span wire:loading>{{ __('panel.loading') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</x-containers.card-container>
