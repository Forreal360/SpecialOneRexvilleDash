@section('title', __('panel.clients'))
@section('description', __('panel.client_management'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.breadcrumb_clients') }}</flux:breadcrumbs.item>
@endsection



@section('actions')
<x-buttons.button-module
    icon="plus"
    href="{{route('v1.panel.clients.create')}}"
    label="{{ __('panel.new_client') }}"
    variant="primary"
/>
@endsection

<x-containers.card-container>

        <x-table.table
        :data="$clients"
        :perPageOptions="$perPageOptions"
        :currentPerPage="$perPage"
        :search="$search"
        searchPlaceholder="{{ __('panel.search') }}"
    >

        <x-slot name="filters">
            <flux:field class="w-full">
                <flux:label>{{ __('panel.name') }}</flux:label>
                <flux:input wire:model.live="name" size="sm" placeholder="{{ __('panel.name') }}" />
            </flux:field>

            <flux:field class="w-full">
                <flux:label>{{ __('panel.email') }}</flux:label>
                <flux:input wire:model.live="email" size="sm" placeholder="{{ __('panel.email') }}" />
            </flux:field>

            <flux:field class="w-full">
                <flux:label>{{ __('panel.phone') }}</flux:label>
                <flux:input wire:model.live="phone" size="sm" placeholder="{{ __('panel.phone') }}" />
            </flux:field>

            <flux:field class="w-full">
                <flux:label>{{ __('panel.license_number') }}</flux:label>
                <flux:input wire:model.live="license_number" size="sm" placeholder="{{ __('panel.license_number') }}" />
            </flux:field>

            <flux:field class="w-full">
                <flux:label>{{ __('panel.status') }}</flux:label>
                <flux:select wire:model.live="status" size="sm" placeholder="{{ __('panel.status') }}">
                    <flux:select.option value="A">{{ __('panel.active') }}</flux:select.option>
                    <flux:select.option value="I">{{ __('panel.inactive') }}</flux:select.option>
                </flux:select>
            </flux:field>
        </x-slot>

        <x-slot name="colums">

            <x-table.colum
                sortable="true"
                sortField="name"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.name') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="email"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.email') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="phone"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.phone') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="license_number"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.license_number') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="status"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.status') }}
            </x-table.colum>
            <x-table.colum
                sortable="true"
                sortField="created_at"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.created_at') }}
            </x-table.colum>
            <x-table.colum>{{ __('panel.actions') }}</x-table.colum>
        </x-slot>
        <x-slot name="rows">
            @foreach($clients as $client)
            @php
                $timezone = session('timezone') ?? 'UTC';
                $created_at = \Carbon\Carbon::parse($client->created_at);
                $created_at = dateToLocal($created_at, $timezone);
            @endphp
            <x-table.row>
                <x-table.cell>
                    {{$client->name}} {{$client->last_name}}
                </x-table.cell>
                <x-table.cell>
                    {{$client->email}}
                </x-table.cell>
                <x-table.cell>
                    {{$client->phone_code}} {{$client->phone}}
                </x-table.cell>
                <x-table.cell>
                    {{$client->license_number}}
                </x-table.cell>
                <x-table.cell>
                    <flux:badge color="{{$client->status == 'A' ? 'lime' : 'red'}}">
                        {{$client->status == 'A' ? __('panel.active') : __('panel.inactive') }}
                    </flux:badge>
                </x-table.cell>
                <x-table.cell>
                    {{$created_at->format('m/d/Y H:i')}}
                </x-table.cell>
                <x-table.cell>

                    <flux:button.group>
                        <flux:button size="sm" icon="pencil" icon:variant="outline" class="cursor-pointer" href="{{ route('v1.panel.clients.edit', $client->id) }}"></flux:button>


                        @if($client->status == 'A')
                        <flux:tooltip content="{{ __('panel.tooltip_deactivate') }}">
                            <flux:button size="sm" icon="hand-thumb-down" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$client->id}}, 'I')" wire:confirm="{{ __('panel.confirm_deactivate') }} {{ __('panel.client') }}"></flux:button>
                        </flux:tooltip>
                        @else
                        <flux:tooltip content="{{ __('panel.tooltip_activate') }}">
                            <flux:button size="sm" icon="hand-thumb-up" icon:variant="outline" class="cursor-pointer" wire:click="updateStatus({{$client->id}}, 'A')" wire:confirm="{{ __('panel.confirm_activate') }} {{ __('panel.client') }}"></flux:button>
                        </flux:tooltip>
                        @endif

                    </flux:button.group>


                </x-table.cell>
            </x-table.row>
            @endforeach
        </x-slot>
    </x-table.table>

</x-containers.card-container>

