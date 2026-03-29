<x-layouts.app :title="__('Levering product')">
    @push('head')
        @if(session('error'))
            <meta http-equiv="refresh" content="4;url={{ session('redirectUrl', url()->previous()) }}">
        @endif
    @endpush

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white dark:bg-zinc-900 shadow ring-1 ring-black/5 dark:ring-white/5 rounded-lg p-6">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $title ?? 'Levering product' }}</h1>
            @if(session('error'))
                <div class="mt-4 p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 rounded-md">
                    <div class="text-rose-800 dark:text-rose-200">{{ session('error') }}</div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Je wordt over 4 seconden teruggebracht.</div>
                </div>
            @endif

            {{-- header info: product + supplier details --}}
            <div class="mt-4 grid grid-cols-1 gap-2 text-sm text-gray-700 dark:text-gray-300">
                <div><strong>Product:</strong> {{ $product->Naam ?? '-' }}</div>
                <div><strong>Leverancier:</strong> {{ $supplier->Naam ?? '-' }}</div>
                <div><strong>Contactpersoon:</strong> {{ $supplier->ContactPersoon ?? '-' }}</div>
                <div><strong>Mobiel:</strong> {{ $supplier->Mobiel ?? '-' }}</div>
            </div>

            <form method="POST" action="{{ route('suppliers.delivery.store', ['supplierId' => $supplierId, 'productId' => $productId]) }}" class="mt-6 grid grid-cols-1 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aantal producteenheden</label>
                    <input name="aantal" type="number" min="1" value="1" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-gray-100 focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Datum eerstvolgende levering</label>
                    <input name="datum_eerstvolgende" type="date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-gray-100 focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-sky-600 dark:bg-sky-500 text-white rounded-md hover:bg-sky-700 dark:hover:bg-sky-600">Sla op</button>
                    <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-zinc-800 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-zinc-700">Terug</a>
                </div>
            </form>
        </div>
    </main>
</x-layouts.app>
