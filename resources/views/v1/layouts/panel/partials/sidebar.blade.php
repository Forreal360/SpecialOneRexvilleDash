<flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
    <div class="flex justify-center items-center">
        {{-- <flux:brand href="#" logo="{{asset('assets/images/rex.png')}}" class="px-2 dark:hidden" />
        <flux:brand href="#" logo="{{asset('assets/images/rex-white.png')}}" class="px-2 hidden dark:flex" /> --}}
        <img src="{{ asset('assets/images/rex.png') }}" alt="Hyundai de Rexville" class="w-30 dark:hidden">
        <img src="{{ asset('assets/images/rex-white.png') }}" alt="Hyundai de Rexville" class="w-20 hidden dark:flex">
    </div>

    <flux:navlist variant="outline">

        <flux:navlist.item icon="home"  href="{{ route('v1.panel.home') }}" :current="request()->is('*panel/home*')">{{ __('panel.home') }}</flux:navlist.item>

        @can('administrators.get')
        <flux:navlist.item icon="users"  href="{{ route('v1.panel.admins.index') }}" :current="request()->is('*panel/admins*')">{{ __('panel.admins') }}</flux:navlist.item>
        @endcan

        @can('roles.get')
        <flux:navlist.item icon="shield-check"  href="{{ route('v1.panel.roles.index') }}" :current="request()->is('*panel/roles*')">{{ __('roles.roles') }}</flux:navlist.item>
        @endcan


        @can('promotions.get')
        <flux:navlist.item icon="ticket" href="{{ route('v1.panel.promotions.index') }}" :current="request()->is('*panel/promotions*')">{{ __('panel.promotions') }}</flux:navlist.item>
        @endcan

        @can('vehicle-services.get')
        <flux:navlist.item icon="list-bullet" href="{{ route('v1.panel.vehicle-services.index') }}" :current="request()->is('*panel/vehicle-services*')">{{ __('panel.services') }}</flux:navlist.item>
        @endcan

        @can('clients.get')
        <flux:navlist.item icon="users" href="{{ route('v1.panel.clients.index') }}" :current="request()->is('*panel/clients*')">{{ __('panel.clients') }}</flux:navlist.item>
        @endcan

        @can('appointments.get')
        <flux:navlist.item icon="calendar" badge="{{$pending_appointments}}" href="{{ route('v1.panel.appointments.index') }}" :current="request()->is('*panel/appointments*')">{{ __('panel.appointments') }}</flux:navlist.item>
        @endcan

        @can('tickets.get')
        <flux:navlist.item icon="chat-bubble-left-right" badge="{{$pending_tickets}}" href="{{ route('v1.panel.tickets.index') }}" :current="request()->is('*panel/tickets*')">{{ __('panel.tickets') }}</flux:navlist.item>
        @endcan

        {{-- <flux:navlist.group expandable heading="Favorites" class="hidden lg:grid">
            <flux:navlist.item href="#">Marketing site</flux:navlist.item>
            <flux:navlist.item href="#">Android app</flux:navlist.item>
            <flux:navlist.item href="#">Brand guidelines</flux:navlist.item>
        </flux:navlist.group> --}}
    </flux:navlist>
    <flux:spacer />

</flux:sidebar>
