<x-layouts.app :title="__('Leverancier Details')">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white dark:bg-zinc-900 shadow ring-1 ring-black/5 dark:ring-white/5 rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $title ?? 'Leverancier Details' }}</h1>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                    <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                    <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            @endif

            @if($leverancier)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Naam</label>
                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $leverancier->Naam }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Contactpersoon</label>
                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $leverancier->Contactpersoon }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Leveranciernummer</label>
                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $leverancier->Leveranciernummer }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Mobiel</label>
                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $leverancier->Mobiel }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Straatnaam</label>
                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $leverancier->Straatnaam }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Huisnummer</label>
                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $leverancier->Huisnummer }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Postcode</label>
                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $leverancier->Postcode }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Stad</label>
                        <p class="text-base text-gray-900 dark:text-gray-100">{{ $leverancier->Stad }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('leveranciers.edit', $leverancier->Id) }}"
                       class="inline-flex items-center px-4 py-2 bg-sky-600 dark:bg-sky-500 text-white text-sm font-medium rounded-md hover:bg-sky-700 dark:hover:bg-sky-600">
                        Wijzig
                    </a>

                    <a href="{{ route('leveranciers.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-700 dark:hover:bg-gray-600">
                        Terug
                    </a>
                </div>
            @else
                <p class="text-red-600 dark:text-red-400">Leverancier niet gevonden</p>
            @endif
        </div>
    </main>
</x-layouts.app>
