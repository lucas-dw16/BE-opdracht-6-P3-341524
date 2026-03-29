<x-layouts.app :title="__('Dashboard')">
    @push('head')
        @if (session('success'))
            {{-- Keep a short meta refresh only when showing the success message --}}
            <meta http-equiv="refresh" content="3;url={{ route('product.index') }}">
        @endif

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
                /* dark header color */
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
                        {{ $title ?? 'Overzicht Magazijn Jamin' }}</h1>
                    <div class="flex items-center gap-3">
                        @if (session('success'))
                            <div class="inline-flex items-center px-3 py-1 rounded-md bg-emerald-500 text-white shadow">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- optional: add a refresh button or create new product button here in future -->
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="sticky-header bg-gray-50 dark:bg-zinc-900">
                            <tr>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Barcode</th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Naam</th>
                                <th scope="col"
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Verpakkingseinheid (kg)</th>
                                <th scope="col"
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Aantal</th>
                                <th scope="col"
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Allergenen</th>
                                <th scope="col"
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Leverantie</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($products as $product)
                                <tr
                                    class="odd:bg-white even:bg-gray-50 odd:dark:bg-zinc-900 even:dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $product->Barcode }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="max-w-xs truncate" title="{{ $product->Naam }}">{{ $product->Naam }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-gray-100">
                                        {{ $product->VerpakkingsEenheid }}</td>
                                    <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-gray-100">
                                        {{ $product->AantalAanwezig }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <a href="{{ route('product.allergenenInfo', $product->Id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-600 dark:bg-red-500 text-white text-sm rounded-md hover:bg-red-700 dark:hover:bg-red-600 ring-0 dark:ring-1 dark:ring-white/10">Allergenen
                                            Info</a>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <a href="{{ route('product.leverantieInfo', $product->Id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-600 dark:bg-green-500 text-white text-sm rounded-md hover:bg-green-700 dark:hover:bg-green-600 ring-0 dark:ring-1 dark:ring-white/10">Leverantie
                                            Info</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
                                        class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">Er zijn
                                        geen producten beschikbaar in het magazijn.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </main>
</x-layouts.app>
