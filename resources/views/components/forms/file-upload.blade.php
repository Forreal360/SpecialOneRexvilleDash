@props([
    'name' => 'file',
    'label' => '',
    'accept' => 'image/*',
    'placeholder' => 'Seleccionar archivo',
    'changeText' => null,
    'selectText' => null,
    'uploadingText' => null,
    'fileReadyText' => null,
    'fileSelectedText' => null,
    'clickToSelectText' => null,
    'dropText' => null,
    'error' => null,
    'required' => false,
    'wireModel' => null,
])

@php
    // Valores por defecto usando traducciones
    $changeText = $changeText ?? __('panel.change_file');
    $selectText = $selectText ?? __('panel.select_file');
    $uploadingText = $uploadingText ?? __('panel.uploading_file');
    $fileReadyText = $fileReadyText ?? __('panel.file_ready');
    $fileSelectedText = $fileSelectedText ?? __('panel.file_selected');
    $clickToSelectText = $clickToSelectText ?? __('panel.click_to_select_file');
    $dropText = $dropText ?? __('panel.drop_file_here');
@endphp

<div x-data="{
    uploading: false,
    progress: 0,
    fileName: '',
    isDragOver: false,
    handleFileSelect(files) {
        if (files.length > 0) {
            const file = files[0];
            this.fileName = file.name;
            const input = document.getElementById('{{ $name }}_input');
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
}"
     x-on:livewire-upload-start="uploading = true; progress = 0"
     x-on:livewire-upload-finish="uploading = false"
     x-on:livewire-upload-cancel="uploading = false"
     x-on:livewire-upload-error="uploading = false"
     x-on:livewire-upload-progress="progress = $event.detail.progress">

    <!-- Área de subida personalizada -->
    <div class="relative">
        <!-- Input de archivo oculto -->
        <input
            id="{{ $name }}_input"
            name="{{ $name }}"
            type="file"
            class="hidden"
            @if($wireModel) wire:model.live="{{ $wireModel }}" @endif
            accept="{{ $accept }}"
            @if($required) required @endif
            x-on:change="fileName = $event.target.files[0]?.name || ''"
        />

                <!-- Área de drag and drop -->
        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center
        transition-all duration-200 cursor-pointer min-h-[180px] flex flex-col justify-center items-center"
             :class="isDragOver ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'hover:border-blue-400 dark:hover:border-blue-500'"

             @click="document.getElementById('{{ $name }}_input').click()"

             @dragover.prevent="isDragOver = true"
             @dragleave.prevent="isDragOver = false"
             @drop.prevent="
                isDragOver = false;
                handleFileSelect($event.dataTransfer.files);
             "

             @dragenter.prevent
             @dragstart.prevent>

            <!-- Ícono dinámico -->
            <div class="mx-auto w-12 h-12 mb-3">
                <svg x-show="!isDragOver" class="w-full h-full text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-5l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                <svg x-show="isDragOver" class="w-full h-full text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
            </div>

            <!-- Texto dinámico -->
            <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                <span x-show="!isDragOver && !fileName" x-text="'{{ $clickToSelectText }}'">
                    {{ $clickToSelectText }}
                </span>
                <span x-show="!isDragOver && fileName" x-text="'{{ $fileSelectedText }}: ' + fileName">
                    {{ $fileSelectedText }}
                </span>
                <span x-show="isDragOver" x-text="'{{ $dropText }}'">
                    {{ $dropText }}
                </span>
            </div>

            <!-- Botón de acción -->
            <button x-show="!isDragOver" type="button"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors"
                    x-text="fileName ? '{{ $changeText }}' : '{{ $selectText }}'">
                {{ $selectText }}
            </button>
        </div>

        <!-- Barra de progreso -->
        <div x-show="uploading" x-transition class="mt-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $uploadingText }}</span>
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
                <span class="text-sm text-green-700 dark:text-green-300" x-text="'{{ $fileReadyText }}: ' + fileName">
                    {{ $fileReadyText }}
                </span>
            </div>
        </div>

        <!-- Mensaje de error -->
        @if($error)
            <div class="mt-2">
                <span class="text-sm text-red-600 dark:text-red-400">{{ $error }}</span>
            </div>
        @endif
    </div>
</div>
