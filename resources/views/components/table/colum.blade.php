@props(['sortable' => false, 'sortField' => null, 'currentSortBy' => null, 'currentSortDirection' => null])

<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
    @if($sortable && $sortField)
        <button
            wire:click="sort('{{ $sortField }}')"
            class="group inline-flex items-center hover:text-gray-700 dark:hover:text-zinc-300 transition-colors duration-200"
        >
            {{ $slot }}
            <span class="ml-2 flex-none rounded">
                @if($currentSortBy === $sortField)
                    @if($currentSortDirection === 'asc')
                        <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    @endif
                @else
                    <svg class="h-4 w-4 text-gray-300 group-hover:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                @endif
            </span>
        </button>
    @else
        {{ $slot }}
    @endif
</th>
