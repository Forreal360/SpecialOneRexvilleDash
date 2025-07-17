<div>
    <flux:heading class="text-center" size="xl">Welcome back</flux:heading>

    <form wire:submit.prevent="login" class="space-y-4">
        <div class="flex flex-col gap-6">
            <flux:input
                label="Email"
                type="email"
                placeholder="email@example.com"
                wire:model.defer="email"
                :invalid="$errors->has('email')"
            />

            <flux:field>
                <div class="mb-3 flex justify-between">
                    <flux:label>Password</flux:label>
                    <flux:link href="{{ route('password.request') }}" variant="subtle" class="text-sm">Forgot password?</flux:link>
                </div>
                <flux:input
                    type="password"
                    placeholder="Your password"
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
                Log in
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
