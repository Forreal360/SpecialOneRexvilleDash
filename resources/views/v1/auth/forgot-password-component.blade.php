<div>
    <flux:heading class="text-center" size="xl">{{ __('auth.recover_password') }}</flux:heading>

    <form wire:submit.prevent="sendResetLink" class="space-y-4 mt-6">
        <div class="flex flex-col gap-6">
            <flux:input
                label="{{ __('auth.email') }}"
                type="email"
                placeholder="{{ __('auth.email_placeholder') }}"
                wire:model.defer="email"
                :invalid="$errors->has('email')"
            />


            <flux:button
                variant="primary"
                class="w-full"
                type="submit"
                wire:loading.attr="disabled"
            >
                {{ __('auth.send_reset_link') }}
            </flux:button>
        </div>
    </form>

    @if (session('status'))
        <div class="mt-4 text-green-600 text-center text-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-accent hover:underline dark:text-zinc-200">{{ __('auth.back_to_login') }}</a>
    </div>
</div>
