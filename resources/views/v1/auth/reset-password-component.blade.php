<div>
    <flux:heading class="text-center" size="xl">Restablecer contraseña</flux:heading>

    <form wire:submit.prevent="resetPassword" class="space-y-4 mt-6">
        <div class="flex flex-col gap-6">
            <flux:input
                label="Email"
                type="email"
                placeholder="email@example.com"
                wire:model.defer="email"
                :invalid="$errors->has('email')"
            />
            @error('email')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror

            <flux:input
                label="Nueva contraseña"
                type="password"
                placeholder="Tu nueva contraseña"
                wire:model.defer="password"
                :invalid="$errors->has('password')"
            />
            @error('password')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror

            <flux:input
                label="Confirmar contraseña"
                type="password"
                placeholder="Confirma tu nueva contraseña"
                wire:model.defer="password_confirmation"
                :invalid="$errors->has('password_confirmation')"
            />
            @error('password_confirmation')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror

            <flux:button
                variant="primary"
                class="w-full"
                type="submit"
                wire:loading.attr="disabled"
            >
                Restablecer contraseña
            </flux:button>
        </div>
    </form>

    @if (session('status'))
        <div class="mt-4 text-green-600 text-center text-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-accent hover:underline">Volver al login</a>
    </div>
</div>
