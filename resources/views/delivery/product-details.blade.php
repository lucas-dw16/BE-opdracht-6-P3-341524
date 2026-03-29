<x-layouts.app :title="$title">
    <main class="flex-1 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="bg-white dark:bg-zinc-900 shadow ring-1 ring-black/5 dark:ring-white/5 rounded-lg p-6">
                <div class="mb-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $title }}
                        </h1>
                        <a
                            href="{{ route('delivery.overview') }}"
                            class="inline-flex items-center rounded-md bg-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-400 dark:bg-zinc-700 dark:text-white dark:hover:bg-zinc-600"
                        >
                            ← Terug naar overzicht
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 rounded-md border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-700 dark:bg-green-950/30 dark:text-green-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-700 dark:bg-red-950/40 dark:text-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="overflow-x-auto rounded-md border border-gray-200 dark:border-zinc-700 mb-5">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                            <tr>
                                <td class="w-1/2 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Naam Product:</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $product->Naam }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Barcode:</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $product->Barcode }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Bevat gluten</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $product->BevatGluten }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Bevat gelatine</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $product->BevatGelatine }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Bevat AZO-kleurstof</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $product->BevatAzoKleurstof }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Bevat lactose</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $product->BevatLactose }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Bevat soja</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $product->BevatSoja }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <form method="POST" action="{{ route('delivery.product-delete', ['productId' => $product->Id]) }}">
                    @csrf
                    <input type="hidden" name="current_date" value="2024-05-25">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600"
                    >
                        Verwijder
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-layouts.app>
