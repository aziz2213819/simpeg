{{-- ================= ALERT SUKSES ================= --}}
@if (session('success'))
    <div id="alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
        class="flex items-center p-4 text-emerald-800 border-l-4 border-emerald-500 bg-emerald-50 rounded-r-lg shadow-sm transition-all duration-500"
        role="alert">
        <svg class="shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"></path>
        </svg>
        <div class="ml-3 text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
@endif

{{-- ================= ALERT GAGAL / ERROR ================= --}}
@if (session('error'))
    <div id="alert-error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
        class="flex items-center p-4 text-red-800 border-l-4 border-red-500 bg-red-50 rounded-r-lg shadow-sm transition-all duration-500"
        role="alert">
        <svg class="shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                clip-rule="evenodd"></path>
        </svg>
        <div class="ml-3 text-sm font-medium">
            {{ session('error') }}
        </div>
    </div>
@endif