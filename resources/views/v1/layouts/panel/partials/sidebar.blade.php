<flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
    <flux:brand href="#" logo="{{asset('assets/images/logo.png')}}" name="Hyundai de Rexville" class="px-2 dark:hidden" />
    <flux:brand href="#" logo="{{asset('assets/images/logo.png')}}" name="Hyundai de Rexville" class="px-2 hidden dark:flex" />

    <flux:navlist variant="outline">
        <flux:navlist.item icon="home" wire:navigate href="{{ route('v1.panel.home') }}" :current="request()->is('panel/home')">{{ __('panel.home') }}</flux:navlist.item>

        <flux:navlist.item icon="users" wire:navigate href="{{ route('v1.panel.admins.index') }}" :current="request()->is('panel/admins')">{{ __('panel.admins') }}</flux:navlist.item>

        {{-- <flux:navlist.group expandable heading="Favorites" class="hidden lg:grid">
            <flux:navlist.item href="#">Marketing site</flux:navlist.item>
            <flux:navlist.item href="#">Android app</flux:navlist.item>
            <flux:navlist.item href="#">Brand guidelines</flux:navlist.item>
        </flux:navlist.group> --}}
    </flux:navlist>
    <flux:spacer />

</flux:sidebar>
