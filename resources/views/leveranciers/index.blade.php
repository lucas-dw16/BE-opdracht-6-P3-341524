<x-layouts.app :title="__('Overzicht Leveranciers')">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white dark:bg-zinc-900 shadow ring-1 ring-black/5 dark:ring-white/5 rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $title ?? 'Overzicht Leveranciers' }}</h1>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-900 sticky-header">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Naam</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Contactpersoon</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Leveranciernummer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Mobiel</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Leverancier Details</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Toon Producten</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900">
                        @forelse($leveranciers as $leverancier)
                            <tr class="odd:bg-white even:bg-gray-50 odd:dark:bg-zinc-900 even:dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $leverancier->Naam }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $leverancier->ContactPersoon }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $leverancier->Leveranciernummer }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $leverancier->Mobiel }}</td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <a href="{{ route('leveranciers.details', ['id' => $leverancier->Id]) }}"
                                       class="inline-flex items-center text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <a href="{{ route('suppliers.products', ['id' => $leverancier->Id]) }}" class="inline-flex items-center px-3 py-1.5 bg-sky-600 dark:bg-sky-500 text-white text-sm rounded-md hover:bg-sky-700 dark:hover:bg-sky-600">Toon</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">Geen leveranciers gevonden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($leveranciers->hasPages())
                <div class="mt-6">
                    {{ $leveranciers->links() }}
                </div>
            @endif

        </div>
    </main>
</x-layouts.app>
