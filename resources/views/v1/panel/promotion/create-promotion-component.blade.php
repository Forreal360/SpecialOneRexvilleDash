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
                <div x-data="{ uploading: false, progress: 0, fileName: '' }"
                     x-on:livewire-upload-start="uploading = true; progress = 0"
                     x-on:livewire-upload-finish="uploading = false"
                     x-on:livewire-upload-cancel="uploading = false"
                     x-on:livewire-upload-error="uploading = false"
                     x-on:livewire-upload-progress="progress = $event.detail.progress">

                    <!-- Área de subida personalizada -->
                    <div class="relative">
                        <!-- Input de archivo oculto -->
                        <input
                            id="customUploadInput"
                            type="file"
                            class="hidden"
                            wire:model.live="file"
                            accept="image/*"
                            x-on:change="fileName = $event.target.files[0]?.name || ''"
                        />

                        <!-- Botón de subida personalizado -->
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-blue-400 dark:hover:border-blue-500 transition-colors cursor-pointer"
                             onclick="document.getElementById('customUploadInput').click()">

                            <!-- Ícono de carpeta -->
                            <div class="mx-auto w-12 h-12 mb-4">
                                <svg class="w-full h-full text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-5l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                            </div>

                                                        <!-- Texto del botón -->
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <span x-text="fileName ? '{{ __('panel.file_selected') }}: ' + fileName : '{{ __('panel.click_to_select_image') }}'">
                                    {{ __('panel.click_to_select_image') }}
                                </span>
                            </div>

                            <!-- Botón de acción -->
                            <button type="button"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors"
                                    x-text="fileName ? '{{ __('panel.change_file') }}' : '{{ __('panel.select_image') }}'">
                                {{ __('panel.select_image') }}
                            </button>
                        </div>

                                                <!-- Barra de progreso -->
                        <div x-show="uploading" x-transition class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('panel.uploading_file') }}</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="progress + '%'">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300 ease-out"
                                     x-bind:style="'width: ' + progress + '%'"></div>
                            </div>
                        </div>

                        <!-- Confirmación de archivo subido -->
                        <div x-show="!uploading && fileName" x-transition class="mt-4">
                            <div class="flex items-center space-x-2 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-green-700 dark:text-green-300" x-text="'{{ __('panel.file_ready') }}: ' + fileName">
                                    {{ __('panel.file_ready') }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
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
                >
                    {{ __('panel.cancel') }}
                </flux:button>
                <flux:button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    wire:target="createPromotion"
                >
                    <span wire:loading.remove wire:target="createPromotion">{{ __('panel.create_promotion') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</x-containers.card-container>
