<flux:header class="block! bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <flux:navbar class="w-full">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <div class="flex items-center gap-x-5">
            <flux:dropdown x-data="{
                currentLocale: '{{ app()->getLocale() }}',
                changeLanguage(locale) {
                    this.currentLocale = locale;

                    // Pequeño delay para mostrar el cambio visual
                    setTimeout(() => {
                        // Construir la nueva URL manteniendo los parámetros existentes
                        let url = new URL(window.location);
                        url.searchParams.set('lang', locale);

                        // Redirigir a la nueva URL
                        window.location.href = url.toString();
                    }, 150);
                }
            }" align="end">
                <flux:button variant="subtle" square class="group" aria-label="{{ __('panel.language_selector') }}" title="{{ __('panel.change_language') }}">
                    <flux:icon.language variant="mini" class="text-zinc-500 dark:text-white ml-1" />
                </flux:button>
                <flux:menu>
                    <flux:menu.item
                        x-on:click="changeLanguage('es')"
                        :class="{ 'bg-blue-50 dark:bg-blue-900  /20': currentLocale === 'es' }"
                        class="flex items-center justify-between"
                    >
                        <span>🇪🇸 Español</span>
                        <flux:icon x-show="currentLocale === 'es'" name="check" class="w-4 h-4 text-blue-500" />
                    </flux:menu.item>
                    <flux:menu.item
                        x-on:click="changeLanguage('en')"
                        :class="{ 'bg-blue-50 dark:bg-blue-900/20': currentLocale === 'en' }"
                        class="flex items-center justify-between"
                    >
                        <span>🇺🇸 English</span>
                        <flux:icon x-show="currentLocale === 'en'" name="check" class="w-4 h-4 text-blue-500" />
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>

            <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle" aria-label="Toggle dark mode" />


            {{-- <flux:dropdown x-data align="end">
                <flux:button variant="subtle" square class="group" aria-label="{{ __('panel.color_scheme_selector') }}">
                    <flux:icon.sun x-show="$flux.appearance === 'light'" variant="mini" class="text-zinc-500 dark:text-white" />
                    <flux:icon.moon x-show="$flux.appearance === 'dark'" variant="mini" class="text-zinc-500 dark:text-white" />
                    <flux:icon.moon x-show="$flux.appearance === 'system' && $flux.dark" variant="mini" />
                    <flux:icon.sun x-show="$flux.appearance === 'system' && ! $flux.dark" variant="mini" />
                </flux:button>
                <flux:menu>
                    <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">{{ __('panel.light_mode') }}</flux:menu.item>
                    <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">{{ __('panel.dark_mode') }}</flux:menu.item>
                </flux:menu>
            </flux:dropdown> --}}

            <livewire:panel.notification.get-notifications-component />

            <flux:dropdown position="top" align="end" >
                <flux:profile avatar="https://fluxui.dev/img/demo/user.png"/>
                <flux:menu>
                                            <flux:menu.item icon="user">{{ __('panel.welcome') }}, {{auth()->user()->name}}</flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-2 px-2 py-1 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded">
                                                    <flux:menu.item icon="arrow-right-start-on-rectangle" onclick="this.closest('form').submit()">
                            {{ __('panel.logout') }}
                        </flux:menu.item>
                        </button>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:navbar>
</flux:header>
