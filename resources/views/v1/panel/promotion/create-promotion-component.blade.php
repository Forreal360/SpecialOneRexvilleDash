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
    <form wire:submit.prevent="createPromotion">
        <div class="flex-1 space-y-6">

            <x-forms.form-field label="{{ __('panel.image') }}*" for="file" :error="$errors->first('file')">
                <x-forms.file-upload
                    name="file"
                    wireModel="file"
                    accept="image/*"
                    :error="$errors->first('file')"
                    required
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
                <x-forms.flatpickr-date
                    name="start_date"
                    wire:model="start_date"
                    dateFormat="m/d/Y"
                    placeholder="{{ __('panel.start_date') }}"
                    minDate="today"
                    error="{{ $errors->first('start_date') }}"
                    required
                />
            </x-forms.form-field>
            <x-forms.form-field label="{{ __('panel.end_date') }}*" for="end_date" :error="$errors->first('end_date')">
                <x-forms.flatpickr-date
                    name="end_date"
                    wire:model="end_date"
                    dateFormat="m/d/Y"
                    placeholder="{{ __('panel.end_date') }}"
                    minDate="today"
                    error="{{ $errors->first('end_date') }}"
                    required
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
                    variant="danger"
                >
                    {{ __('panel.cancel') }}
                </flux:button>
                <flux:button
                    wire:click="createPromotion"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    wire:target="createPromotion"
                    variant="primary"
                >
                    <span wire:loading.remove wire:target="createPromotion">{{ __('panel.create_promotion') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</x-containers.card-container>
