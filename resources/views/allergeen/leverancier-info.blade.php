<x-layouts.app :title="$title">
    <main class="flex-1 w-full">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="bg-white dark:bg-zinc-900 shadow ring-1 ring-black/5 dark:ring-white/5 rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $title ?? 'Overzicht leverancier gegevens' }}
                    </h1>
                    <a href="{{ route('allergeen.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                        Terug
                    </a>
                </div>

                {{-- Product Information --}}
                <div class="mb-6 pb-6 border-b border-gray-200 dark:border-zinc-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Product Informatie</h2>
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Naam Product</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->Naam }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Barcode</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->Barcode }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Supplier Information --}}
                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Leverancier Informatie</h2>
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Naam Leverancier</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leverancier->LeverancierNaam }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Contactpersoon</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leverancier->Contactpersoon }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Leveranciernummer</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leverancier->Leveranciernummer }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Mobiel</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <a href="tel:{{ $leverancier->Mobiel }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ $leverancier->Mobiel }}
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Contact Information or Warning --}}
                @if($hasContactInfo)
                    <div class="pt-6 border-t border-gray-200 dark:border-zinc-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Contactgegevens</h2>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Straat</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leverancier->Straat }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Huisnummer</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leverancier->Huisnummer }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Postcode</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leverancier->Postcode }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Stad</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leverancier->Stad }}</dd>
                            </div>
                        </dl>
                    </div>
                @else
                    <div class="pt-6 border-t border-gray-200 dark:border-zinc-700">
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 dark:border-yellow-600 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-400">
                                        Er zijn geen adresgegevens bekend
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
</x-layouts.app>
