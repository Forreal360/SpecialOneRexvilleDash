<!-- Image input -->
<div class="setting-item" wire:ignore>
    <div class="flex flex-col items-center justify-center w-full">
        <img id="preview_image" src="{{ $previewImage ?? '' }}" class="mb-3 rounded border" style="width: 150px; height: 150px; object-fit: cover;">


        <flux:button
            type="button"
            id="cropper_button"
            onclick="openCropperModal()"
        >
            {{ $buttonText ?? 'Cargar Imagen' }}
        </flux:button>


        <small class="text-gray-500">Formatos permitidos: .jpg, .jpeg, .png. Tamaño máximo: 10MB</small>
        <input type="hidden" wire:model="{{ $wireModel ?? 'cropped_image' }}" id="cropped_image">
        @error("{{$wireModel ?? 'image'}}")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<!-- Modal Tailwind -->
<div id="cropper_modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-6">
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h5 class="text-2xl  text-primary">Selecciona y Recorta la Imagen</h5>
            <button type="button" class="text-gray-400 hover:text-gray-700 text-2xl font-bold transition" onclick="closeCropperModal()">&times;</button>
        </div>
        <div class="flex flex-col items-center">
            <input type="file" id="input_image" accept=".jpg,.jpeg,.png" class="mb-4 w-full max-w-xs border rounded px-3 py-2">
            <div class="text-red-600 text-sm mb-2 hidden" id="file_error">Formato o tamaño no permitido. Formatos permitidos: .jpg, .jpeg, .png. Tamaño máximo: 10MB</div>
            <div class="flex justify-center w-full">
                <img id="modal_image" class="max-h-96 rounded border shadow" />
            </div>
        </div>
        <div class="flex justify-end gap-4 border-t pt-4 mt-4">
            <flux:button type="button" onclick="closeCropperModal()" color="secondary">
                Cancelar
            </flux:button>
            <flux:button type="button" id="cropButton" color="primary">
                Recortar y Guardar
            </flux:button>
        </div>
    </div>
</div>
