<tbody class="bg-white divide-y divide-gray-200 dark:divide-zinc-700 dark:bg-zinc-800">
    @forelse($admins as $admin)
    <tr class="hover:bg-gray-50 transition-colors duration-200 dark:hover:bg-zinc-700 dark:text-zinc-200">

        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-zinc-400">
            {{ $admin->email }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <svg class="w-2 h-2 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3"/>
                </svg>
                Activo
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-zinc-400">
            {{ $admin->created_at ? $admin->created_at->format('d/m/Y H:i') : 'N/A' }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center">
                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron administradores</h3>
                <p class="text-gray-500">
                    @if($search)
                        No hay resultados que coincidan con "{{ $search }}"
                    @else
                        Aún no hay resultados registrados en el sistema
                    @endif
                </p>
            </div>
        </td>
    </tr>
    @endforelse
</tbody>
