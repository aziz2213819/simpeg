<x-layouts::pegawai_app :title="__('Notifikasi')">
    <flux:heading size="xl" level="1">Notifikasi, {{ $user->employee->name ?? 'Pegawai' }}</flux:heading>
    <flux:text class="mt-2 text-base">Ini adalah daftar pemberitahuan dan pembaruan terbaru untuk Anda.</flux:text>

    <flux:separator variant="subtle" />

    {{-- Alert Sukses (Menggunakan Alpine.js yang sudah kita pelajari) --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
            class="mt-4 p-3 bg-green-50 text-emerald-700 rounded-lg text-sm border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Daftar Notifikasi --}}
    <div class="mt-6 space-y-4">
        @forelse($notifications as $notif)
            {{--
            Desain Dinamis:
            Jika belum dibaca -> Background biru muda (highlight)
            Jika sudah dibaca -> Background putih biasa
            --}}
            <div
                class="relative p-5 border rounded-xl flex flex-col sm:flex-row gap-4 sm:items-center transition-all 
                                                    {{ $notif->is_read ? 'bg-white border-zinc-200' : 'bg-emerald-50/40 border-emerald-200 shadow-sm' }}">

                {{-- Indikator Titik Biru untuk pesan yang belum dibaca --}}
                @if(!$notif->is_read)
                    <div class="absolute top-5 right-5 w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></div>
                @endif

                <div class="flex-1">
                    {{-- Badge Tipe & Waktu --}}
                    <div class="flex items-center gap-2 mb-1.5">
                        <flux:badge size="sm" color="{{ $notif->is_read ? 'zinc' : 'emerald' }}">
                            {{ Str::headline($notif->type) }}
                        </flux:badge>
                        {{-- Mengubah timestamp menjadi format ramah seperti "2 jam yang lalu" --}}
                        <span class="text-xs {{ $notif->is_read ? 'text-zinc-500' : 'text-emerald-600 font-medium' }}">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>

                    {{-- Judul & Pesan --}}
                    <h3 class="text-base font-semibold {{ $notif->is_read ? 'text-zinc-800' : 'text-emerald-900' }}">
                        {{ $notif->title }}
                    </h3>
                    <p class="text-sm mt-1 {{ $notif->is_read ? 'text-zinc-600' : 'text-emerald-800' }}">
                        {{ $notif->message }}
                    </p>
                </div>

                {{-- Tombol Aksi (Hanya muncul jika belum dibaca) --}}
                @if(!$notif->is_read)
                    {{-- <div class="mt-3 sm:mt-0 border-t sm:border-t-0 pt-3 sm:pt-0 border-emerald-100">
                        <form action="{{ route('pegawai.notifikasi.show', $notif->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <flux:button type="submit" size="sm" variant="outline" class="w-full sm:w-auto">
                                Tandai Dibaca
                            </flux:button>
                        </form>
                    </div> --}}
                    <flux:modal.trigger name="mark_is_read-{{ $notif->id }}">
                        <flux:button size="sm" variant="outline" class="w-full sm:w-auto">
                            Tandai Dibaca
                        </flux:button>
                    </flux:modal.trigger>
                    <flux:modal name="mark_is_read-{{ $notif->id }}" class="min-w-88">
                        {{-- Form diarahkan ke fungsi destroy di Controller --}}
                        <form action="{{ route('pegawai.notifikasi.show', $notif->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="space-y-6">
                                <div>
                                    <flux:heading size="lg">Tandai sebagai Dibaca</flux:heading>
                                    <flux:subheading>
                                        Apakah Anda yakin ingin menandai notifikasi ini sebagai dibaca?
                                    </flux:subheading>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <flux:modal.close>
                                        <flux:button variant="ghost">Batal</flux:button>
                                    </flux:modal.close>

                                    <flux:button type="submit" color="emerald" variant="primary">
                                        Ya, Tandai Dibaca
                                    </flux:button>
                                </div>
                            </div>
                        </form>
                    </flux:modal>
                @endif
            </div>
        @empty
            {{-- Tampilan Kosong (Empty State) yang Rapi --}}
            <div class="text-center py-16 text-zinc-500 bg-zinc-50 rounded-xl border border-dashed border-zinc-200 mt-6">
                <svg class="mx-auto h-12 w-12 text-zinc-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <p class="font-medium">Belum ada notifikasi.</p>
                <p class="text-sm mt-1">Anda sudah membaca semua pembaruan sistem.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</x-layouts::pegawai_app>