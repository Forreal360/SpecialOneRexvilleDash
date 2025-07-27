@section('title', __('panel.ticket_number') . $ticket->id)
@section('description', $ticket->subject)

@section('breadcrumbs')
<flux:breadcrumbs.item href="{{route('v1.panel.home')}}" separator="slash">{{ __('panel.breadcrumb_home') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item href="{{route('v1.panel.tickets.index')}}" separator="slash">{{ __('panel.tickets') }}</flux:breadcrumbs.item>
<flux:breadcrumbs.item separator="slash">{{ __('panel.ticket_number') }}{{ $ticket->id }}</flux:breadcrumbs.item>
@endsection

@section('actions')
@if($ticket->status !== 'closed')
    <flux:button
        wire:click="closeTicket"
        wire:confirm="{{ __('panel.confirm_close_ticket') }}"
        variant="danger"
        icon="x-mark"
    >
        {{ __('panel.close_ticket_button') }}
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
                            <span class="font-medium">{{ __('panel.client_label') }}</span>
                            {{ $ticket->client->name }} {{ $ticket->client->last_name }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">{{ __('panel.email_label') }}</span>
                            {{ $ticket->client->email }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">{{ __('panel.created_label') }}</span>
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
                            'open' => __('panel.open'),
                            'in_progress' => __('panel.in_progress'),
                            'closed' => __('panel.closed')
                        ];
                    @endphp
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusClasses[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                    </span>
                </div>
            </div>
            <flux:separator class="mb-4" />
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('panel.conversation') }}</h3>

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
                            {{ __('panel.no_messages') }}
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
                                placeholder="{{ __('panel.write_response') }}"
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
                                icon="paper-airplane"

                            >
                                {{ __('panel.send_message_button') }}
                            </flux:button>
                        </div>
                    </form>
                </div>
            @else
                <div class="border-t pt-4 text-center text-sm text-gray-500">
                    {{ __('panel.ticket_closed_no_messages') }}
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
