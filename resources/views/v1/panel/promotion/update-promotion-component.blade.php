@section('title', __('panel.promotions'))
@section('description', __('panel.create_promotion'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.promotions.index')}}" separator="slash">{{ __('panel.breadcrumb_promotions') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_create') }}</flux:breadcrumbs.item>
@endsection

@section('actions')

@endsection

<x-containers.card-container>
    <form wire:submit.prevent="updatePromotion">
        <div class="flex-1 space-y-6">

            <x-forms.form-field label="{{ __('panel.image') }}" for="title" :error="$errors->first('file')">
                <flux:input
                    type="file"
                    id="image"
                    wire:model="file"
                    placeholder="Título de la promoción"
                        error="{{ $errors->first('file') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.title') }}*" for="title" :error="$errors->first('title')">
                <flux:input
                    id="title"
                    wire:model="title"
                    placeholder="Título de la promoción"
                    error="{{ $errors->first('title') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.start_date') }}*" for="start_date" :error="$errors->first('start_date')">
                <flux:input
                    id="start_date"
                    wire:model="start_date"
                    type="date"
                    value="{{ $start_date }}"
                    error="{{ $errors->first('start_date') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.end_date') }}*" for="end_date" :error="$errors->first('end_date')">
                <flux:input
                    id="end_date"
                    wire:model="end_date"
                    type="date"
                    error="{{ $errors->first('end_date') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.redirect_url') }}" for="redirect_url" :error="$errors->first('redirect_url')">
                <flux:input
                    id="redirect_url"
                    wire:model="redirect_url"
                    placeholder="URL de redirección (opcional)"
                    error="{{ $errors->first('redirect_url') }}"
                />
            </x-forms.form-field>


            <div class="flex justify-end space-x-3 pt-0 px-6 pb-6">
                <flux:button
                    href="{{route('v1.panel.promotions.index')}}"
                    type="button"
                >
                    {{ __('panel.cancel') }}
                </flux:button>
                <flux:button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                >
                    <span wire:loading.remove>{{ __('panel.update_promotion') }}</span>
                    <span wire:loading>{{ __('panel.loading') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</x-containers.card-container>
