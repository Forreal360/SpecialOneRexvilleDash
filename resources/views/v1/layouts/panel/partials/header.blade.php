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

            <flux:dropdown align="end">
                <flux:button variant="subtle" square class="group relative" aria-label="{{ __('panel.notifications_selector') }}">
                    <flux:icon.bell variant="mini" class="text-zinc-500 dark:text-white" />
                    <flux:badge variant="danger" size="sm" class="absolute -top-1 -right-1" >2</flux:badge>
                </flux:button>
                <flux:menu class="p-0">
                    <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-center justify-between">
                            <flux:heading size="sm">{{ __('panel.notifications') }}</flux:heading>
                            <flux:button variant="subtle" size="xs" class="text-xs">
                                {{ __('panel.mark_all_as_read') }}
                            </flux:button>
                        </div>
                    </div>

                    <div class="max-h-80 overflow-y-auto">
                        @php
                            $notifications = [
                                ['id' => 1, 'title' => __('panel.new_order_received'), 'message' => __('panel.order_received_message', ['order_id' => '12345']), 'time' => '2 min', 'read' => false, 'type' => 'order'],
                                ['id' => 2, 'title' => __('panel.system_update'), 'message' => __('panel.system_update_message', ['time' => '30 minutos']), 'time' => '1 hora', 'read' => false, 'type' => 'system'],
                                ['id' => 3, 'title' => __('panel.client_registered'), 'message' => __('panel.client_registered_message', ['name' => 'Juan Pérez']), 'time' => '3 horas', 'read' => true, 'type' => 'user'],
                                ['id' => 4, 'title' => __('panel.low_stock'), 'message' => __('panel.low_stock_message', ['product' => 'Laptop HP']), 'time' => '5 horas', 'read' => true, 'type' => 'inventory'],
                                ['id' => 5, 'title' => __('panel.backup_completed'), 'message' => __('panel.backup_completed_message'), 'time' => '1 día', 'read' => true, 'type' => 'system']
                            ];
                        @endphp

                        @foreach($notifications as $notification)
                            <div class="px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800 cursor-pointer border-b border-zinc-100 dark:border-zinc-700 last:border-b-0 {{ !$notification['read'] ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-1">
                                        @php
                                            $icon = match($notification['type']) {
                                                'order' => 'shopping-cart',
                                                'system' => 'cog',
                                                'user' => 'user',
                                                default => 'cube'
                                            };
                                        @endphp
                                        <flux:icon
                                            icon="{{ $icon }}"
                                            variant="mini"
                                            class="{{ $notification['read'] ? 'text-zinc-400' : 'text-blue-500' }}"
                                        />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <flux:text
                                                size="sm"
                                                class="{{ $notification['read'] ? 'text-zinc-600 dark:text-zinc-300' : 'font-medium text-zinc-900 dark:text-white' }}"
                                            >
                                                {{ $notification['title'] }}
                                            </flux:text>
                                            <flux:text size="xs" class="text-zinc-400">{{ $notification['time'] }}</flux:text>
                                        </div>
                                        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2">
                                            {{ $notification['message'] }}
                                        </flux:text>
                                    </div>
                                    @if(!$notification['read'])
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:button variant="subtle" class="w-full" size="sm">
                            {{ __('panel.view_all_notifications') }}
                        </flux:button>
                    </div>
                </flux:menu>
            </flux:dropdown>
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
