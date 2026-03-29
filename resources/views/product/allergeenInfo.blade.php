<x-layouts.app :title="__('Allergeen Informatie')">
    @push('head')
        @if (isset($hasAllergenen) && !$hasAllergenen)
            <meta http-equiv="refresh" content="4;url={{ route('product.index') }}">
        @endif
    @endpush

    <main class="flex-1 w-full">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="bg-white dark:bg-zinc-900 shadow ring-1 ring-black/5 dark:ring-white/5 rounded-lg p-6">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $title ?? 'Allergeen Informatie' }}
                </h1>

                <div class="mt-4 text-sm text-gray-700 dark:text-gray-300">
                    @if (!empty($productName) || !empty($productBarcode))
                        <p><strong>Naam:</strong> {{ $productName ?? '—' }}</p>
                        <p><strong>Barcode:</strong> {{ $productBarcode ?? '—' }}</p>
                    @else
                        <p>Product ID: {{ $productId }}</p>
                    @endif
                </div>

                @if (isset($hasAllergenen) && !$hasAllergenen)
                    <div
                        class="mt-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 rounded-md">
                        <div class="text-emerald-800 dark:text-emerald-200">In dit product zitten geen stoffen die een
                            allergische reactie kunnen veroorzaken.</div>
                        <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">Je wordt over 4 seconden teruggebracht
                            naar het overzicht.</div>
                    </div>
                @else
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-zinc-700">
                            <thead class="bg-gray-50 dark:bg-zinc-900">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Naam</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Omschrijving</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-900">
                                @foreach ($allergenen as $a)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $a->Naam }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $a->Omschrijving }}</td>
                                    </tr>
                                @endforeach
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

    @if (isset($hasAllergenen) && !$hasAllergenen)
        <script>
            setTimeout(function() {
                window.location.href = '{{ route('product.index') }}';
            }, 4000);
        </script>
    @endif
</x-layouts.app>
