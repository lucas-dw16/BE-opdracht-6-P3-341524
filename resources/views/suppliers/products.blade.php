<x-layouts.app :title="__('Geleverde producten')">
    @push('head')
        @if(empty($products) || count($products) === 0)
            <meta http-equiv="refresh" content="3;url={{ route('suppliers.index') }}">
        @endif
    @endpush

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white dark:bg-zinc-900 shadow ring-1 ring-black/5 dark:ring-white/5 rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $title ?? 'Geleverde producten' }}</h1>
            </div>

            @if(!empty($supplier))
                <div class="grid grid-cols-2 gap-4 mb-4 text-sm text-gray-700 dark:text-gray-300">
                    <div><strong>Naam leverancier:</strong> {{ $supplier->Naam ?? '-' }}</div>
                    <div><strong>Contactpersoon:</strong> {{ $supplier->ContactPersoon ?? '-' }}</div>
                    <div><strong>Leverancier NR:</strong> {{ $supplier->Leveranciernummer ?? '-' }}</div>
                    <div><strong>Mobiel:</strong> {{ $supplier->Mobiel ?? '-' }}</div>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-900 sticky-header">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Product</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aantal in magazijn</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Verpakkingseenheid</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Laatste levering</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nieuwe levering</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900">
                        @forelse($products as $p)
                            <tr class="odd:bg-white even:bg-gray-50 odd:dark:bg-zinc-900 even:dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $p->Naam }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-gray-100">{{ $p->AantalInMagazijn }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-gray-100">{{ $p->VerpakkingsEenheid ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-gray-100">{{ isset($p->LaatsteLevering) ? date('d-m-Y', strtotime($p->LaatsteLevering)) : '-' }}</td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <a href="{{ route('suppliers.delivery', ['supplierId' => $supplierId, 'productId' => $p->ProductId]) }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 dark:bg-emerald-500 text-white text-sm rounded-md hover:bg-emerald-700 dark:hover:bg-emerald-600">+</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <div>Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin</div>
                                    <div class="mt-2 text-xs text-gray-400 dark:text-gray-500">Je wordt over 3 seconden teruggebracht naar het overzicht.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="/leveranciers" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-zinc-800 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-zinc-700">Terug</a>
            </div>

        </div>
    </main>
</x-layouts.app>
