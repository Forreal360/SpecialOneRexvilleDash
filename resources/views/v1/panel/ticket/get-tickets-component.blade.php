@section('title', __('panel.tickets_support'))
@section('description', __('panel.tickets_management'))

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.tickets') }}</flux:breadcrumbs.item>
@endsection

<x-containers.card-container>

    <x-table.table
        :data="$tickets"
        :perPageOptions="$perPageOptions"
        :currentPerPage="$perPage"
        :search="$search"
        searchPlaceholder="{{ __('panel.search_tickets') }}"
    >

        <x-slot name="filters">
            <flux:field class="w-full">
                <flux:label>{{ __('panel.client') }}</flux:label>
                <flux:select wire:model.live="client_id" size="sm" placeholder="{{ __('panel.select_client') }}">
                    @foreach($clients as $client)
                        <flux:select.option value="{{ $client->id }}">{{ $client->name }} {{ $client->last_name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field class="w-full">
                <flux:label>{{ __('panel.status') }}</flux:label>
                <flux:select wire:model.live="status" size="sm" placeholder="{{ __('panel.select_status') }}">
                    @foreach($statusOptions as $value => $label)
                        <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        </x-slot>

        <x-slot name="colums">

            <x-table.colum
                sortable="true"
                sortField="subject"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.subject') }}
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="client"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.client') }}
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
                sortField="updated_at"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                {{ __('panel.last_activity') }}
            </x-table.colum>

            <x-table.colum>
                {{ __('panel.actions') }}
            </x-table.colum>

        </x-slot>

        <x-slot name="rows">
            @foreach ($tickets as $ticket)
                <x-table.row>

                    <x-table.cell>
                        {{ $ticket->subject }}
                        @if($ticket->lastMessage)
                            <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                                {{ __('panel.last_message') }} {{ Str::limit($ticket->lastMessage->message, 50) }}
                                @if($ticket->new_message_from_client == "Y")
                                    <span class="relative flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-lime-500 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-lime-600"></span>
                                    </span>
                                @endif
                            </div>
                        @endif
                    </x-table.cell>

                    <x-table.cell>
                        {{ $ticket->client->name }} {{ $ticket->client->last_name }}
                        <div class="text-sm text-gray-500">
                            {{ $ticket->client->email }}
                        </div>
                    </x-table.cell>

                    <x-table.cell>
                        @php
                            $statusClasses = [
                                'open' => 'bg-green-100 text-green-800',
                                'in_progress' => 'bg-yellow-100 text-yellow-800',
                                'closed' => 'bg-gray-100 text-gray-800'
                            ];
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClasses[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusOptions[$ticket->status] ?? $ticket->status }}
                        </span>
                    </x-table.cell>

                    <x-table.cell>
                        {{ $ticket->updated_at->format('d/m/Y H:i') }}
                    </x-table.cell>

                    <x-table.cell>
                        <flux:button.group>
                            <flux:tooltip content="{{ __('panel.view_ticket') }}">
                                <flux:button
                                    href="{{ route('v1.panel.tickets.view', $ticket->id) }}"
                                    size="sm"
                                    icon="eye"
                                    icon:variant="outline"
                                >
                                </flux:button>
                            </flux:tooltip>

                            @if($ticket->status !== 'closed')
                                <flux:tooltip content="{{ __('panel.close_ticket_tooltip') }}">
                                    <flux:button
                                        wire:click="closeTicket({{ $ticket->id }})"
                                        wire:confirm="{{ __('panel.confirm_close_ticket') }}"
                                        size="sm"
                                        icon="x-circle"
                                        icon:variant="outline"
                                        >
                                    </flux:button>
                                </flux:tooltip>
                            @endif
                        </flux:button.group>
                    </x-table.cell>

                </x-table.row>
            @endforeach
        </x-slot>

    </x-table.table>

</x-containers.card-container>
