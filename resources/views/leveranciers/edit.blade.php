<x-layouts.app :title="__('Wijzig Leveranciergegevens')">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white dark:bg-zinc-900 shadow ring-1 ring-black/5 dark:ring-white/5 rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $title ?? 'Wijzig Leveranciergegevens' }}</h1>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                    <ul class="list-disc list-inside text-sm text-red-800 dark:text-red-200">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('leveranciers.update', $leverancier->Id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="contact_id" value="{{ $leverancier->ContactId }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="naam" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Naam</label>
                        <input type="text"
                               id="naam"
                               name="naam"
                               value="{{ old('naam', $leverancier->Naam) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                               required>
                    </div>

                    <div>
                        <label for="contactpersoon" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Contactpersoon</label>
                        <input type="text"
                               id="contactpersoon"
                               name="contactpersoon"
                               value="{{ old('contactpersoon', $leverancier->Contactpersoon) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                               required>
                    </div>

                    <div>
                        <label for="leveranciernummer" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Leveranciernummer</label>
                        <input type="text"
                               id="leveranciernummer"
                               name="leveranciernummer"
                               value="{{ old('leveranciernummer', $leverancier->Leveranciernummer) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                               required>
                    </div>

                    <div>
                        <label for="mobiel" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Mobiel</label>
                        <input type="text"
                               id="mobiel"
                               name="mobiel"
                               value="{{ old('mobiel', $leverancier->Mobiel) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                               required>
                    </div>

                    <div>
                        <label for="straatnaam" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Straatnaam</label>
                        <input type="text"
                               id="straatnaam"
                               name="straatnaam"
                               value="{{ old('straatnaam', $leverancier->Straatnaam) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                               required>
                    </div>

                    <div>
                        <label for="huisnummer" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Huisnummer</label>
                        <input type="text"
                               id="huisnummer"
                               name="huisnummer"
                               value="{{ old('huisnummer', $leverancier->Huisnummer) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                               required>
                    </div>

                    <div>
                        <label for="postcode" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Postcode</label>
                        <input type="text"
                               id="postcode"
                               name="postcode"
                               value="{{ old('postcode', $leverancier->Postcode) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                               required>
                    </div>

                    <div>
                        <label for="stad" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Stad</label>
                        <input type="text"
                               id="stad"
                               name="stad"
                               value="{{ old('stad', $leverancier->Stad) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400"
                               required>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-700 dark:hover:bg-green-600">
                        Sla Op
                    </button>

                    <a href="{{ route('leveranciers.details', $leverancier->Id) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-700 dark:hover:bg-gray-600">
                        Terug
                    </a>
                </div>
            </form>
        </div>
    </main>
</x-layouts.app>
