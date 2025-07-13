<div class="relative">
    <flux:dropdown>
        <flux:dropdown.trigger>
            <flux:button size="sm" variant="outline" class="flex items-center gap-2">
                <span class="w-4 h-4">
                    @if($currentLocale === 'es')
                        🇪🇸
                    @else
                        🇺🇸
                    @endif
                </span>
                <span>{{ $availableLocales[$currentLocale] }}</span>
                <flux:icon name="chevron-down" class="w-4 h-4" />
            </flux:button>
        </flux:dropdown.trigger>

        <flux:dropdown.content>
            @foreach($availableLocales as $locale => $name)
                <flux:dropdown.item
                    wire:click="changeLanguage('{{ $locale }}')"
                    class="flex items-center gap-2 cursor-pointer {{ $currentLocale === $locale ? 'bg-gray-100' : '' }}"
                >
                    <span class="w-4 h-4">
                        @if($locale === 'es')
                            🇪🇸
                        @else
                            🇺🇸
                        @endif
                    </span>
                    <span>{{ $name }}</span>
                    @if($currentLocale === $locale)
                        <flux:icon name="check" class="w-4 h-4 ml-auto" />
                    @endif
                </flux:dropdown.item>
            @endforeach
        </flux:dropdown.content>
    </flux:dropdown>
</div>
