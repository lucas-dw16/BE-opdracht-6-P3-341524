<x-layouts.app :title="$title">
    @push('head')
        <style>
            .result-panel {
                min-height: 28rem;
            }

            .pagination-link {
                min-width: 2.25rem;
            }
        </style>
    @endpush

    <main class="flex-1 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="bg-white dark:bg-zinc-900 shadow ring-1 ring-black/5 dark:ring-white/5 rounded-lg p-6">
                <h1 class="mb-4 text-2xl font-semibold text-gray-900 dark:text-white">
                    {{ $title }}
                </h1>

                @if(session('error'))
                    <div class="mb-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-700 dark:bg-red-950/40 dark:text-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="GET" action="{{ route('delivery.overview') }}" class="mb-6 rounded-lg bg-gray-50 p-4 dark:bg-zinc-800">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4 md:items-end">
                        <div>
                            <label for="start_date" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Startdatum
                            </label>
                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                value="{{ $startDate ?? '' }}"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-red-500 focus:outline-none focus:ring-red-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white"
                            >
                            @error('start_date')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Einddatum
                            </label>
                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                value="{{ $endDate ?? '' }}"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-red-500 focus:outline-none focus:ring-red-500 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white"
                            >
                            @error('end_date')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <div class="flex flex-wrap gap-2">
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600"
                                >
                                    Maak selectie
                                </button>
                                <a
                                    href="{{ route('delivery.overview') }}"
                                    class="inline-flex items-center justify-center rounded-md bg-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-400 dark:bg-zinc-600 dark:text-white dark:hover:bg-zinc-500"
                                >
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="result-panel">
                    @if($hasFilter && count($products) === 0)
                        <div class="rounded-md border border-gray-200 px-4 py-6 text-center text-sm text-gray-500 dark:border-zinc-700 dark:text-gray-300">
                            Geen producten gevonden binnen dit tijdsvak.
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-md border border-gray-200 dark:border-zinc-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                                <thead class="bg-gray-100 dark:bg-zinc-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Naam leverancier</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Contactpersoon</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Stad</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Productnaam</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Einddatum levering</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Verwijder</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                                    @forelse($products as $product)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800">
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $product->LeverancierNaam ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $product->Contactpersoon ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $product->Stad ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $product->ProductNaam }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($product->EinddatumLevering)->format('d-m-Y') }}</td>
                                            <td class="px-4 py-3 text-center text-sm">
                                                <a
                                                    href="{{ route('delivery.product-details', ['productId' => $product->ProductId]) }}"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-red-600 text-base font-bold text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600"
                                                    title="Verwijder product"
                                                >
                                                    X
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                                Vul een start- en einddatum in om een selectie te maken.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                @if(isset($pagination) && $pagination->last_page > 1)
                    <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 pt-4 dark:border-zinc-700">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Toont {{ $pagination->from }} tot {{ $pagination->to }} van {{ $pagination->total }} resultaten
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if($pagination->current_page > 1)
                                <a
                                    href="{{ route('delivery.overview', ['start_date' => $startDate, 'end_date' => $endDate, 'page' => $pagination->current_page - 1]) }}"
                                    class="pagination-link rounded-md bg-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-300 dark:bg-zinc-700 dark:text-gray-300 dark:hover:bg-zinc-600"
                                >Vorige</a>
                            @endif
                            @for($i = 1; $i <= $pagination->last_page; $i++)
                                <a
                                    href="{{ route('delivery.overview', ['start_date' => $startDate, 'end_date' => $endDate, 'page' => $i]) }}"
                                    class="pagination-link rounded-md px-3 py-2 text-sm {{ $i === $pagination->current_page ? 'bg-red-600 text-white dark:bg-red-500' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-zinc-700 dark:text-gray-300 dark:hover:bg-zinc-600' }}"
                                >{{ $i }}</a>
                            @endfor
                            @if($pagination->current_page < $pagination->last_page)
                                <a
                                    href="{{ route('delivery.overview', ['start_date' => $startDate, 'end_date' => $endDate, 'page' => $pagination->current_page + 1]) }}"
                                    class="pagination-link rounded-md bg-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-300 dark:bg-zinc-700 dark:text-gray-300 dark:hover:bg-zinc-600"
                                >Volgende</a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
</x-layouts.app>
