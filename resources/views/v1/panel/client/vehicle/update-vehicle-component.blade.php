@section('title', __('panel.vehicles'))
@section('description', __('panel.edit_vehicle'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.clients.index')}}" separator="slash">{{ __('panel.breadcrumb_clients') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.vehicles.index', $clientId)}}" separator="slash">{{ __('panel.breadcrumb_vehicles') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_edit') }}</flux:breadcrumbs.item>
@endsection

@section('actions')

@endsection

<x-containers.card-container>
    <form wire:submit.prevent="updateVehicle">
        <div class="flex-1 space-y-6">

            <x-forms.form-field label="{{ __('panel.image') }}" for="image" :error="$errors->first('image')">
                <x-forms.file-upload
                    name="image"
                    wireModel="image"
                    accept="image/*"
                    :error="$errors->first('image')"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.year') }}*" for="year" :error="$errors->first('year')">
                <flux:input
                    id="year"
                    wire:model="year"
                    type="number"
                    min="1900"
                    max="{{ date('Y') + 1 }}"
                    placeholder="2024"
                    error="{{ $errors->first('year') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.make') }}*" for="make_id" :error="$errors->first('make_id')">
                <flux:select
                    id="make_id"
                    wire:model.live="make_id"
                    error="{{ $errors->first('make_id') }}"
                >
                    <flux:select.option value="">{{ __('panel.select_make') }}</flux:select.option>
                    @foreach($makes as $make)
                        <flux:select.option value="{{ $make->id }}">{{ $make->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.model') }}*" for="model_id" :error="$errors->first('model_id')">
                <flux:select
                    id="model_id"
                    wire:model.live="model_id"
                    error="{{ $errors->first('model_id') }}"
                    
                >
                    <flux:select.option value="">{{ $make_id ? __('panel.select_model') : __('panel.select_make_first') }}</flux:select.option>
                    @foreach($models as $model)
                        <flux:select.option value="{{ $model->id }}">{{ $model->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.vin') }}*" for="vin" :error="$errors->first('vin')">
                <flux:input
                    id="vin"
                    wire:model="vin"
                    placeholder="1HGBH41JXMN109186"
                    error="{{ $errors->first('vin') }}"
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.buy_date') }}*" for="buy_date" :error="$errors->first('buy_date')">
                <x-forms.flatpickr-date
                    name="buy_date"
                    wire:model="buy_date"
                    dateFormat="m/d/Y"
                    placeholder="{{ __('panel.buy_date') }}"
                    error="{{ $errors->first('buy_date') }}"
                    required
                />
            </x-forms.form-field>

            <x-forms.form-field label="{{ __('panel.insurance') }}*" for="insurance" :error="$errors->first('insurance')">
                <flux:input
                    id="insurance"
                    wire:model="insurance"
                    placeholder="ABC Insurance Company"
                    error="{{ $errors->first('insurance') }}"
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
                    href="{{route('v1.panel.vehicles.index', $clientId)}}"
                    type="button"
                >
                    {{ __('panel.cancel') }}
                </flux:button>
                <flux:button
                    wire:click="updateVehicle"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    wire:target="updateVehicle"
                >
                    <span wire:loading.remove wire:target="updateVehicle">{{ __('panel.update_vehicle') }}</span>
                    <span wire:loading wire:target="updateVehicle">{{ __('panel.loading') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</x-containers.card-container> 