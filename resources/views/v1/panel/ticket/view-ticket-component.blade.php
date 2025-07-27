@section('title', 'Ticket #' . $ticket->id)
@section('description', $ticket->subject)

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.tickets.index')}}" separator="slash">Tickets</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">Ticket #{{ $ticket->id }}</flux:breadcrumbs.item>
@endsection

@section('actions')
@if($ticket->status !== 'closed')
    <flux:button
        wire:click="closeTicket"
        wire:confirm="¿Estás seguro de que quieres cerrar este ticket?"
        variant="danger"
        icon="x-mark"
    >
        Cerrar Ticket
    </flux:button>
@endif
@endsection

<div class="space-y-6">

    <!-- Chat de Mensajes -->
    <x-containers.card-container>
        <div class="p-6 pt-0">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="mt-0 space-y-1">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Cliente:</span>
                            {{ $ticket->client->name }} {{ $ticket->client->last_name }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Email:</span>
                            {{ $ticket->client->email }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Creado:</span>
                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    @php
                        $statusClasses = [
                            'open' => 'bg-green-100 text-green-800',
                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                            'closed' => 'bg-gray-100 text-gray-800'
                        ];
                        $statusLabels = [
                            'open' => 'Abierto',
                            'in_progress' => 'En Progreso',
                            'closed' => 'Cerrado'
                        ];
                    @endphp
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusClasses[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                    </span>
                </div>
            </div>
            <flux:separator class="mb-4" />
            <h3 class="text-lg font-medium text-gray-900 mb-4">Conversación</h3>

            <!-- Mensajes -->
            <div class="space-y-4 mb-6 max-h-96 min-h-96 overflow-y-auto">
                @forelse($ticket->messages as $message)
                    <div class="flex {{ $message->isFromAdmin() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            <div class="flex items-start space-x-2 {{ $message->isFromAdmin() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full {{ $message->isFromAdmin() ? 'bg-blue-500' : 'bg-gray-400' }} flex items-center justify-center">
                                        <span class="text-xs font-medium text-white">
                                            {{ substr($message->fromeable->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Mensaje -->
                                <div class="flex-1">
                                    <div class="px-4 py-2 rounded-lg {{ $message->isFromAdmin() ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-900' }}">
                                        <p class="text-sm">{{ $message->message }}</p>
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500 {{ $message->isFromAdmin() ? 'text-right' : 'text-left' }}">
                                        {{ $message->fromeable->name }} · {{ $message->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-sm">
                            No hay mensajes en este ticket aún.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Formulario para enviar mensaje -->
            @if($ticket->status !== 'closed')
                <div class="border-t pt-4">
                    <form wire:submit="sendMessage" class="space-y-4">
                        <div>
                            <flux:textarea
                                wire:model="newMessage"
                                placeholder="Escribe tu respuesta..."
                                rows="3"
                                class="w-full"
                            />
                            @error('newMessage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <flux:button
                                type="submit"
                                variant="primary"
                                icon="paper-airplane"

                            >
                                Enviar Mensaje
                            </flux:button>
                        </div>
                    </form>
                </div>
            @else
                <div class="border-t pt-4 text-center text-sm text-gray-500">
                    Este ticket está cerrado. No se pueden enviar más mensajes.
                </div>
            @endif
        </div>
    </x-containers.card-container>
</div>

<script>
    // Auto-scroll al último mensaje cuando se envía uno nuevo
    document.addEventListener('livewire:init', () => {
        Livewire.on('message-sent', () => {
            setTimeout(() => {
                const chatContainer = document.querySelector('.max-h-96.overflow-y-auto');
                if (chatContainer) {
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            }, 100);
        });
    });

    // Scroll al último mensaje al cargar
    window.addEventListener('load', () => {
        const chatContainer = document.querySelector('.max-h-96.overflow-y-auto');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>
