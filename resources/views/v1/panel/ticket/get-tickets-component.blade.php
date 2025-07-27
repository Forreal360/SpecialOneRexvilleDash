@section('title', 'Tickets de Soporte')
@section('description', 'Gestión de tickets de soporte')

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">Tickets</flux:breadcrumbs.item>
@endsection

<x-containers.card-container>

    <x-table.table
        :data="$tickets"
        :perPageOptions="$perPageOptions"
        :currentPerPage="$perPage"
        :search="$search"
        searchPlaceholder="Buscar tickets..."
    >

        <x-slot name="filters">
            <flux:field class="w-full">
                <flux:label>Cliente</flux:label>
                <flux:select wire:model.live="client_id" size="sm" placeholder="Seleccionar cliente">
                    @foreach($clients as $client)
                        <flux:select.option value="{{ $client->id }}">{{ $client->name }} {{ $client->last_name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field class="w-full">
                <flux:label>Estado</flux:label>
                <flux:select wire:model.live="status" size="sm" placeholder="Seleccionar estado">
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
                Asunto
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="client"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                Cliente
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="status"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                Estado
            </x-table.colum>

            <x-table.colum
                sortable="true"
                sortField="updated_at"
                :currentSortBy="$sortBy"
                :currentSortDirection="$sortDirection"
            >
                Última Actividad
            </x-table.colum>

            <x-table.colum>
                Acciones
            </x-table.colum>

        </x-slot>

        <x-slot name="rows">
            @foreach ($tickets as $ticket)
                <x-table.row>

                    <x-table.cell>
                        <div class="font-medium text-gray-900">
                            {{ $ticket->subject }}
                        </div>
                        @if($ticket->lastMessage)
                            <div class="text-sm text-gray-500 mt-1">
                                Último mensaje: {{ Str::limit($ticket->lastMessage->message, 50) }}
                            </div>
                        @endif
                    </x-table.cell>

                    <x-table.cell>
                        <div class="font-medium text-gray-900">
                            {{ $ticket->client->name }} {{ $ticket->client->last_name }}
                        </div>
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
                        <div class="text-sm text-gray-900">
                            {{ $ticket->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </x-table.cell>

                    <x-table.cell>
                        <flux:button.group>
                            <flux:tooltip content="Ver ticket">
                                <flux:button
                                    href="{{ route('v1.panel.tickets.view', $ticket->id) }}"
                                    size="sm"
                                    icon="eye"
                                    icon:variant="outline"
                                >
                                </flux:button>
                            </flux:tooltip>

                            @if($ticket->status !== 'closed')
                                <flux:tooltip content="Cerrar ticket">
                                    <flux:button
                                        wire:click="closeTicket({{ $ticket->id }})"
                                        wire:confirm="¿Estás seguro de que quieres cerrar este ticket?"
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
