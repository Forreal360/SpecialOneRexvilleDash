<div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach ($this->stats as $stat)
            <div class="relative rounded-lg px-4 py-3 md:px-6 md:py-4 bg-zinc-50 dark:bg-zinc-700 flex-1">
                <flux:subheading>{{ $stat['title'] }}</flux:subheading>
                <flux:heading size="xl" class="mb-2">{{ $stat['value'] }}</flux:heading>
            </div>
        @endforeach
    </div>

    {{-- Componente Flatpickr --}}
    @if(env('APP_ENV') === 'local')
    <div class="mt-8 p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-gray-200 dark:border-zinc-700">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-zinc-100">Componente Flatpickr</h3>
        <p class="text-gray-600 dark:text-zinc-400 mb-4">Selector de fechas personalizable con formato ajustable</p>
        <div class="flex gap-4 flex-wrap">
            <flux:button
                href="{{ route('test') }}"
                variant="primary"
                size="sm"
                icon="calendar"
            >
                Probar Componente
            </flux:button>
        </div>
    </div>
    @endif
</div>
