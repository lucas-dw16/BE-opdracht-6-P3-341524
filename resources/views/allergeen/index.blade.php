<x-layouts.app :title="$title">
    @push('head')
        <style>
            /* sticky header background for light mode */
            .sticky-header th {
                position: sticky;
                top: 0;
                background: white;
                z-index: 5;
            }

            /* sticky header for dark mode */
            .dark .sticky-header th {
                background: #0b1220;
                border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            }

            /* subtle row divider in dark mode for better separation */
            .dark tbody tr td {
                border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            }
        </style>
    @endpush

    <main class="flex-1 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="bg-white dark:bg-zinc-900 shadow ring-1 ring-black/5 dark:ring-white/5 rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $title ?? 'Overzicht Allergenen' }}
                    </h1>
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                        Terug naar Home
                    </a>
                </div>

                {{-- Filter Form --}}
                <form method="GET" action="{{ route('allergeen.index') }}" class="mb-6">
                    <div class="flex items-end gap-4">
                        <div class="flex-1">
                            <label for="allergeen_id" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">
                                Allergeen:
                            </label>
                            <select name="allergeen_id" id="allergeen_id"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-800 dark:text-white">
                                <option value="">-- Alle allergenen --</option>
                                @foreach($allergenen as $allergeen)
                                    <option value="{{ $allergeen->Id }}"
                                            {{ $selectedAllergeenId == $allergeen->Id ? 'selected' : '' }}>
                                        {{ $allergeen->Naam }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Maak Selectie
                        </button>
                    </div>
                </form>

                {{-- Products Table --}}
                @if(count($products) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-zinc-700">
                            <thead class="sticky-header bg-gray-50 dark:bg-zinc-900">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Naam Product
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Naam Allergeen
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Omschrijving
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Aantal Aanwezig
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                                        Info
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-800">
                                @foreach($products as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $product->ProductNaam }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-zinc-400">
                                            {{ $product->Allergenen }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-zinc-400">
                                            @if($selectedAllergeenId)
                                                @php
                                                    $selectedAllergeen = collect($allergenen)->firstWhere('Id', $selectedAllergeenId);
                                                @endphp
                                                {{ $selectedAllergeen->Omschrijving ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-zinc-400">
                                            {{ $product->AantalAanwezig ?? '0' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('allergeen.leverancier', $product->ProductId) }}"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 hover:bg-blue-700 text-white transition-colors"
                                               title="Bekijk leverancier informatie">
                                                ?
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($totalPages > 1)
                        <div class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-zinc-700 pt-4 px-4">
                            <div class="text-sm text-gray-700 dark:text-zinc-300">
                                Pagina <span class="font-medium">{{ $currentPage }}</span> van <span class="font-medium">{{ $totalPages }}</span>
                                <span class="ml-2 text-gray-500 dark:text-zinc-400">({{ $totalCount }} totaal)</span>
                            </div>
                            <div class="flex gap-2">
                                <!-- Previous button -->
                                @if($currentPage > 1)
                                    <a href="{{ route('allergeen.index', ['page' => $currentPage - 1, 'allergeen_id' => $selectedAllergeenId]) }}"
                                       class="px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-md text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                                        Vorige
                                    </a>
                                @else
                                    <span class="px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-md text-sm font-medium text-gray-400 dark:text-zinc-600 bg-gray-100 dark:bg-zinc-900 cursor-not-allowed">
                                        Vorige
                                    </span>
                                @endif

                                <!-- Page numbers -->
                                @for($i = 1; $i <= $totalPages; $i++)
                                    @if($i == $currentPage)
                                        <span class="px-4 py-2 border border-blue-600 rounded-md text-sm font-medium text-white bg-blue-600">
                                            {{ $i }}
                                        </span>
                                    @else
                                        <a href="{{ route('allergeen.index', ['page' => $i, 'allergeen_id' => $selectedAllergeenId]) }}"
                                           class="px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-md text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                                            {{ $i }}
                                        </a>
                                    @endif
                                @endfor

                                <!-- Next button -->
                                @if($currentPage < $totalPages)
                                    <a href="{{ route('allergeen.index', ['page' => $currentPage + 1, 'allergeen_id' => $selectedAllergeenId]) }}"
                                       class="px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-md text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                                        Volgende
                                    </a>
                                @else
                                    <span class="px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-md text-sm font-medium text-gray-400 dark:text-zinc-600 bg-gray-100 dark:bg-zinc-900 cursor-not-allowed">
                                        Volgende
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500 dark:text-zinc-400 text-lg">
                            Geen producten gevonden met allergenen.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</x-layouts.app>
