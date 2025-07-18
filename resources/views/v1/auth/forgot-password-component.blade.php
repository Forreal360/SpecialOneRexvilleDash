<div>
    <flux:heading class="text-center" size="xl">Recuperar contraseña</flux:heading>

    <form wire:submit.prevent="sendResetLink" class="space-y-4 mt-6">
        <div class="flex flex-col gap-6">
            <flux:input
                label="Email"
                type="email"
                placeholder="email@example.com"
                wire:model.defer="email"
                :invalid="$errors->has('email')"
            />


            <flux:button
                variant="primary"
                class="w-full"
                type="submit"
                wire:loading.attr="disabled"
            >
                Enviar enlace
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
