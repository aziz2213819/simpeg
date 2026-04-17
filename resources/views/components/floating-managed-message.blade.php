@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" {{-- Animasi meluncur dari
        kanan (slide in/out) --}} x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-10"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 sm:translate-x-10" {{-- Class 'fixed' untuk membuatnya mengambang di pojok kanan
        atas --}}
        class="fixed top-5 right-5 z-50 flex items-center p-4 min-w-75 text-emerald-800 border-l-4 border-emerald-500 bg-emerald-50 rounded-r-lg shadow-lg"
        role="alert">

        {{-- Ikon Ceklis --}}
        <svg class="shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"></path>
        </svg>

        {{-- Pesan Sukses --}}
        <div class="ml-3 text-sm font-medium mr-4">
            {{ session('success') }}
        </div>

        {{-- Tombol Tutup Manual --}}
        <button @click="show = false" type="button"
            class="ml-auto -mx-1.5 -my-1.5 bg-emerald-50 text-emerald-500 rounded-lg focus:ring-2 focus:ring-emerald-400 p-1.5 hover:bg-emerald-200 inline-flex h-8 w-8 items-center justify-center">
            <span class="sr-only">Tutup</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>
@elseif (session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" {{-- Animasi meluncur dari
        kanan (slide in/out) --}} x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-10"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 sm:translate-x-10" {{-- Class 'fixed' untuk membuatnya mengambang di pojok kanan
        atas dengan tema warna merah --}}
        class="fixed top-5 right-5 z-50 flex items-center p-4 min-w-75 text-red-800 border-l-4 border-red-500 bg-red-50 rounded-r-lg shadow-lg"
        role="alert">

        {{-- Ikon Silang (Error) --}}
        <svg class="shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                clip-rule="evenodd"></path>
        </svg>

        {{-- Pesan Error --}}
        <div class="ml-3 text-sm font-medium mr-4">
            {{ session('error') }}
        </div>

        {{-- Tombol Tutup Manual --}}
        <button @click="show = false" type="button"
            class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 items-center justify-center">
            <span class="sr-only">Tutup</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>
@endif