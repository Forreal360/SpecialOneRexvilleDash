@section('title', __('panel.admins'))
@section('description', __('panel.create_admin'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.admins.index')}}" separator="slash">{{ __('panel.breadcrumb_admins') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_create') }}</flux:breadcrumbs.item>
@endsection

@section('actions')

@endsection

<x-containers.card-container>
    <form wire:submit.prevent="createAdmin">
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
                    error="{{ $errors->first('form.name') }}"
                />
            </x-forms.form-field>
            <x-forms.form-field label="{{ __('panel.email') }}*" for="email" :error="$errors->first('form.email')">
                <flux:input
                    id="email"
                    wire:model="email"
                    type="email"
                    placeholder="admin@ejemplo.com"
                    error="{{ $errors->first('form.email') }}"
                />
            </x-forms.form-field>
            <x-forms.form-field label="{{ __('panel.password') }}*" for="password" :error="$errors->first('form.password')">
                <flux:input
                    id="password"
                    wire:model="password"
                    type="password"
                    placeholder="••••••••"
                    error="{{ $errors->first('form.password') }}"
                />
            </x-forms.form-field>
            <x-forms.form-field label="{{ __('panel.password_confirmation') }}*" for="password_confirmation" :error="$errors->first('form.password_confirmation')">
                <flux:input
                    id="password_confirmation"
                    wire:model="password_confirmation"
                    type="password"
                    placeholder="••••••••"
                    error="{{ $errors->first('form.password_confirmation') }}"
                />
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
                    <span wire:loading.remove>{{ __('panel.create_admin') }}</span>
                    <span wire:loading>{{ __('panel.loading') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</x-containers.card-container>
