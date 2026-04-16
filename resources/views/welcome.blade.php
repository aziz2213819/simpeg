<x-layouts::app.landing :title="__('Selamat Datang')">
    <div class="text-zinc-900 font-sans">

        <nav class="flex items-center justify-between px-8 py-6 max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                {{-- Mengganti ikon shield dengan leaf (daun) untuk tema lingkungan --}}
                <flux:icon.globe-americas class="text-emerald-600 w-8 h-8" />
                <span class="text-2xl font-bold tracking-tight dark:text-white">DLH<span
                        class="text-emerald-600">Care</span></span>
            </div>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}"
                    class="hover:text-emerald-600 transition-colors dark:text-white">Beranda</a>
                <a href="#fitur" class="hover:text-emerald-600 transition-colors dark:text-white">Alur Lapor</a>
                <a href="#" class="hover:text-emerald-600 transition-colors dark:text-white">Tentang DLH</a>

                @auth
                    <a href="{{ url(auth()->check() && auth()->user()->employee_id == null ? '/dashboard' : '/homepage') }}"
                        class="block px-5 py-1.5 text-white border border-emerald-600 hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal bg-emerald-600 hover:bg-emerald-700"
                        wire:navigate>
                        Dashboard
                    </a>
                @else
                    <div class="flex gap-4">
                        <flux:button href="{{ route('login') }}"
                            class="block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-emerald-600 hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                            wire:navigate>
                            Log in Petugas
                        </flux:button>

                        @if (Route::has('pengaduan.create'))
                            <flux:button href="{{ route('pengaduan.create') }}" variant="primary"
                                class="block bg-emerald-600 hover:bg-emerald-700 dark:text-white" wire:navigate>
                                Lapor Sebagai Warga
                            </flux:button>
                        @endif
                    </div>
                @endauth
            </div>
        </nav>

        <section class="relative overflow-hidden bg-emerald-50/50 dark:bg-transparent py-20 px-8">
            <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <flux:badge color="green" size="sm">Sistem Layanan Masyarakat Terpadu</flux:badge>
                    <h1 class="text-5xl md:text-6xl font-extrabold leading-tight dark:text-white">
                        Lapor Tumpukan Sampah <br>
                        <span class="text-emerald-600">Lebih Cepat & Mudah.</span>
                    </h1>
                    <p class="text-lg text-zinc-600 max-w-lg dark:text-white">
                        Bantu kami menjaga kebersihan lingkungan dengan melaporkan tumpukan sampah liar, fasilitas
                        kebersihan yang rusak, atau masalah lingkungan di sekitar Anda.
                    </p>
                    <div class="flex gap-4">
                        <flux:button href="{{ route('pengaduan.create') }}" variant="primary"
                            class="bg-emerald-600 px-8 dark:text-white" wire:navigate>Buat Laporan Baru
                        </flux:button>
                        <flux:button variant="ghost">Cek Status Laporan</flux:button>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-emerald-200/50 rounded-full blur-3xl"></div>
                    <div
                        class="relative overflow-hidden aspect-video w-full bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-emerald-100 dark:border-emerald-900/30 flex flex-col">

                        <div
                            class="flex items-center gap-1.5 px-4 py-3 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-white/5">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>
                        </div>

                        <div class="flex-1 flex items-center justify-center bg-emerald-50/30 dark:bg-emerald-950/10">
                            <div class="text-center space-y-3">
                                {{-- Ikon disesuaikan jadi map-pin (lokasi) --}}
                                <flux:icon.map-pin class="mx-auto text-emerald-600 w-12 h-12 opacity-80" />
                                <p
                                    class="text-emerald-700/50 dark:text-emerald-400/50 font-medium text-sm tracking-widest uppercase">
                                    Peta Sebaran Lokasi Laporan
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="fitur" class="py-24 px-8 max-w-7xl mx-auto">
            <div class="text-center mb-16 space-y-4 dark:text-white">
                <h2 class="text-3xl font-bold">Mengapa Melapor Melalui Sistem Kami?</h2>
                <p class="text-zinc-500 dark:text-white max-w-2xl mx-auto">Dirancang untuk memastikan setiap keluhan
                    warga terkait kebersihan lingkungan tertangani secara efektif dan transparan.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 rounded-2xl border border-zinc-200 hover:border-emerald-500 transition-all group">
                    <div
                        class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors">
                        <flux:icon.camera class="text-emerald-600 group-hover:text-white" />
                    </div>
                    <h3 class="text-xl font-bold mb-3 dark:text-white">Laporan Berbasis Geotagging</h3>
                    <p class="text-zinc-500 text-sm leading-relaxed dark:text-white">Lampirkan foto tumpukan sampah
                        beserta titik koordinat lokasi (GPS) agar petugas mudah menemukan titik permasalahan.</p>
                </div>

                <div class="p-8 rounded-2xl border border-zinc-200 hover:border-emerald-500 transition-all group">
                    <div
                        class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors">
                        <flux:icon.clock class="text-emerald-600 group-hover:text-white" />
                    </div>
                    <h3 class="text-xl font-bold mb-3 dark:text-white">Tracking Penanganan Terbuka</h3>
                    <p class="text-zinc-500 text-sm leading-relaxed dark:text-white">Pantau progres laporan Anda secara
                        real-time. Mulai dari status Diterima, Dalam Penanganan, hingga Selesai dibersihkan.</p>
                </div>

                <div class="p-8 rounded-2xl border border-zinc-200 hover:border-emerald-500 transition-all group">
                    <div
                        class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors">
                        <flux:icon.truck class="text-emerald-600 group-hover:text-white" />
                    </div>
                    <h3 class="text-xl font-bold mb-3 dark:text-white">Integrasi Armada Kebersihan</h3>
                    <p class="text-zinc-500 text-sm leading-relaxed dark:text-white">Laporan yang tervalidasi akan
                        langsung diteruskan ke rute operasi truk atau petugas kebersihan (Pasukan Kuning) terdekat.</p>
                </div>
            </div>
        </section>

        <section class="px-8 mb-20">
            <div class="max-w-7xl mx-auto bg-emerald-600 rounded-3xl p-12 text-center text-white space-y-6">
                <h2 class="text-4xl font-bold">Mari Wujudkan Lingkungan Bersih & Asri!</h2>
                <p class="text-emerald-100 max-w-xl mx-auto">Satu laporan dari Anda sangat berarti untuk mencegah
                    pencemaran dan menjaga keindahan kota kita. Jangan ragu untuk melapor.</p>
                <flux:button class="bg-white text-emerald-600 hover:bg-emerald-50 border-none px-10">Lapor Lewat
                    WhatsApp
                </flux:button>
            </div>
        </section>

        <footer class="bg-zinc-950 text-zinc-400 py-16 px-8">
            <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-12">
                <div class="col-span-2 space-y-6">
                    <div class="flex items-center gap-2 text-white">
                        <flux:icon.globe-americas class="w-8 h-8 text-emerald-500" />
                        <span class="text-2xl font-bold">DLH<span class="text-emerald-500">Care</span></span>
                    </div>
                    <p class="max-w-sm">Layanan pengaduan masyarakat resmi di bawah naungan Dinas Lingkungan Hidup,
                        khusus untuk penanganan persampahan dan kebersihan fasilitas umum.</p>
                </div>
                <div class="space-y-4">
                    <h4 class="text-white font-bold">Tautan Cepat</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-emerald-500">Cara Melapor</a></li>
                        <li><a href="#" class="hover:text-emerald-500">FAQ / Bantuan</a></li>
                        <li><a href="#" class="hover:text-emerald-500">Standar Pelayanan (SOP)</a></li>
                    </ul>
                </div>
                <div class="space-y-4">
                    <h4 class="text-white font-bold">Kontak DLH</h4>
                    <ul class="space-y-2 text-sm">
                        <li>pengaduan@dlh.gresikkab.go.id</li> {{-- Sesuaikan nama kotanya jika perlu --}}
                        <li>(031) 1234-5678</li>
                        <li>Jl. Lingkungan Hijau No. 1, Gresik</li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl mx-auto border-t border-zinc-800 mt-12 pt-8 text-center text-xs">
                <p>&copy; 2026 Dinas Lingkungan Hidup Kabupaten Gresik. Hak Cipta Dilindungi.</p>
            </div>
        </footer>

    </div>
</x-layouts::app.landing>