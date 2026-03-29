<x-layouts.app :title="__('Leverantie Informatie')">
    @push('head')
        @if (isset($aantalAanwezig) && ($aantalAanwezig === null || $aantalAanwezig == 0))
            <meta http-equiv="refresh" content="4;url={{ route('product.index') }}">
        @endif
    @endpush

    <main class="flex-1 w-full">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="bg-white dark:bg-zinc-900 shadow ring-1 ring-black/5 dark:ring-white/5 rounded-lg p-6">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $title ?? 'Leverantie Informatie' }}
                </h1>

                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Product ID: <strong
                        class="text-gray-800 dark:text-gray-100">{{ $productId }}</strong></p>

                @if (isset($leveranciers) && count($leveranciers) > 0)
                    @php $sup = $leveranciers[0]; @endphp
                    <div class="mt-4 p-4 bg-gray-50 dark:bg-zinc-800 rounded-md">
                        <div class="text-sm text-gray-700 dark:text-gray-300"><strong>Naam leverancier:</strong>
                            {{ $sup->Naam ?? '-' }}</div>
                        <div class="text-sm text-gray-700 dark:text-gray-300"><strong>Contactpersoon:</strong>
                            {{ $sup->Contactpersoon ?? '-' }}</div>
                        <div class="text-sm text-gray-700 dark:text-gray-300"><strong>Leveranciernummer:</strong>
                            {{ $sup->Leveranciernummer ?? '-' }}</div>
                        <div class="text-sm text-gray-700 dark:text-gray-300"><strong>Mobiel:</strong>
                            {{ $sup->Mobiel ?? '-' }}</div>
                    </div>
                @endif

                @if (isset($aantalAanwezig) && ($aantalAanwezig === null || $aantalAanwezig == 0))
                    @php
                        $next = null;
                        foreach ($deliveries as $d) {
                            if (!empty($d->DatumEerstVolgendeLevering)) {
                                $next = $d->DatumEerstVolgendeLevering;
                                break;
                            }
                        }
                    @endphp

                    <div
                        class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-md">
                        <div class="text-amber-800 dark:text-amber-200 font-medium">Geen voorraad op dit moment</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">Verwachte eerstvolgende levering:
                            <strong
                                class="text-gray-800 dark:text-gray-100">{{ $next ? date('d-m-Y', strtotime($next)) : '-' }}</strong>
                        </div>
                        <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">Je wordt over 4 seconden
                            teruggebracht naar het overzicht.</div>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-zinc-700">
                            <thead class="bg-gray-50 dark:bg-zinc-900">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Datum Levering</th>
                                    <th
                                        class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Aantal</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Verwachte eerstvolgende levering</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-900">
                                <tr>
                                    <td class="px-4 py-3 text-sm" colspan="3">Er is van dit product op dit moment
                                        geen voorraad aanwezig, de verwachte eerstvolgende levering is:
                                        {{ $next ? date('d-m-Y', strtotime($next)) : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-zinc-700">
                            <thead class="bg-gray-50 dark:bg-zinc-900">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Datum Levering</th>
                                    <th
                                        class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Aantal</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Verwachte eerstvolgende levering</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-900">
                                @forelse($deliveries as $d)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                            {{ isset($d->DatumLevering) ? date('d-m-Y', strtotime($d->DatumLevering)) : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-gray-100">
                                            {{ $d->Aantal ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                            {{ isset($d->DatumEerstVolgendeLevering) ? date('d-m-Y', strtotime($d->DatumEerstVolgendeLevering)) : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"
                                            class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">Geen
                                            leveringen bekend</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('product.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-zinc-800 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-zinc-700">←
                        Terug naar overzicht</a>
                </div>
            </div>
        </div>
    </main>

    @if (isset($aantalAanwezig) && ($aantalAanwezig === null || $aantalAanwezig == 0))
        <script>
            setTimeout(function() {
                window.location.href = '{{ route('product.index') }}';
            }, 4000);
        </script>
    @endif
</x-layouts.app>
