<div>
    <flux:heading class="text-center" size="xl">{{ __('auth.welcome_back') }}</flux:heading>

    <form wire:submit.prevent="login" class="space-y-4">
        <div class="flex flex-col gap-6">
            <flux:input
                label="{{ __('auth.email') }}"
                type="email"
                placeholder="{{ __('auth.email_placeholder') }}"
                wire:model.defer="email"
                :invalid="$errors->has('email')"
            />

            <flux:field>
                <div class="mb-3 flex justify-between">
                    <flux:label>{{ __('auth.password') }}</flux:label>
                    <flux:link href="{{ route('password.request') }}" variant="subtle" class="text-sm">{{ __('auth.forgot_password') }}</flux:link>
                </div>
                <flux:input
                    type="password"
                    placeholder="{{ __('auth.password_placeholder') }}"
                    wire:model.defer="password"
                    :invalid="$errors->has('password')"
                />
                <flux:error name="password"/>
            </flux:field>

            <flux:button
                variant="primary"
                class="w-full"
                type="submit"
                wire:loading.attr="disabled"
            >
                {{ __('auth.log_in') }}
            </flux:button>
        </div>
    </form>
</div>

@script('custom-js')
<script>
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    $wire.set('timezone', timezone);
</script>
@endscript
