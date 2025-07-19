@props(['perPageOptions' => [5, 10, 20, 50, 100], 'currentPerPage' => 10, 'searchPlaceholder' => 'Buscar', 'data' => null, 'search' => ''])

<div>
    <div class="px-6 pb-3 border-b border-gray-200 dark:border-zinc-700">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 w-full">
            <x-table.filters>
                {{$filters}}
            </x-table.filters>
        </div>
        <div class="flex mt-3">
            <flux:button
                size="sm"
                variant="danger"
                class="ml-0"
                wire:click="resetFilters"
                icon="arrow-path"
            >
                {{ __('panel.reset_filters') }}
            </flux:button>
        </div>
    </div>
    <div class="px-6 py-4  border-gray-200 dark:border-zinc-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <flux:select wire:model.live="perPage" size="sm" placeholder="{{ __('panel.show_per_page') }}" class="w-full sm:w-32">
                @foreach($perPageOptions as $option)
                    <flux:select.option value="{{ $option }}">{{ $option }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:input
                wire:model.live.debounce.300ms="search"
                size="sm"
                icon="magnifying-glass"
                placeholder="{{ $searchPlaceholder }}"
                class="w-full sm:w-64"
            />
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
            <x-table.colums>
                {{$colums}}
            </x-table.colums>

            <!-- Estado de carga -->


            <!-- Contenido real de la tabla -->
            <tbody wire:loading.remove class="bg-white divide-y divide-gray-200 dark:divide-zinc-700 dark:bg-zinc-800">
                {{$rows}}
            </tbody>
        </table>


        <div wire:loading class="bg-white divide-y divide-gray-200 dark:divide-zinc-700 dark:bg-zinc-800 w-full">
            <div class="flex items-center justify-center space-x-2 py-12">
                <flux:icon.loading />
                <span class="text-sm text-gray-600 dark:text-zinc-400">{{ __('panel.loading_data') }}</span>
            </div>
        </div>

        <!-- Empty state -->
        {{-- <div wire:loading.remove>
            @if($data && $data->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-zinc-100">No se encontraron resultados</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">
                    @if($search)
                        No se encontraron resultados que coincidan con "{{ $search }}"
                    @else
                        No hay datos disponibles
                    @endif
                </p>
            </div>
            @endif
        </div> --}}

        <div wire:loading.remove class="bg-white divide-y divide-gray-200 dark:divide-zinc-700 dark:bg-zinc-800 w-full">
            @if($data && $data->isEmpty())
            <div class="flex items-center justify-center space-x-2 py-12">
                <flux:icon.document-text class="text-gray-600 dark:text-zinc-400" size="lg" />
                @if($search)
                    <span class="text-sm text-gray-600 dark:text-zinc-400">{{ __('panel.no_results_for_search', ['search' => $search]) }}</span>
                @else
                    <span class="text-sm text-gray-600 dark:text-zinc-400">{{ __('panel.no_data_available') }}</span>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Paginación -->
    @if($data && $data->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700" wire:loading.remove>
        <div class="flex items-center justify-end">

            {{ $data->links() }}

        </div>
    </div>
    @endif
</div>
