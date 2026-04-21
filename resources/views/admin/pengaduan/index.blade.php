<x-layouts::app :title="__('Data Pengaduan Masyarakat')">
    <div class="p-6 space-y-6">

        {{-- Jika Anda memakai x-managed-message, biarkan ini. Atau gunakan alert alpine js yang kita buat --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                class="p-4 bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500 rounded-r-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <flux:card>
            {{-- Header & Filter --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <flux:heading size="lg">Data Pengaduan Sampah</flux:heading>
                    <flux:subheading>Daftar laporan kebersihan dari masyarakat.</flux:subheading>
                </div>

                <form action="{{ route('admin.pengaduan.index') }}" method="GET"
                    class="flex flex-col sm:flex-row w-full md:w-auto gap-2">
                    {{-- Filter Status --}}
                    <div class="w-full sm:w-40">
                        <flux:select name="status" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                        </flux:select>
                    </div>

                    {{-- Kotak Pencarian --}}
                    <div class="flex w-full sm:w-auto gap-2">
                        <flux:input name="search" type="search" value="{{ request('search') }}"
                            placeholder="Cari pelapor / lokasi..." class="w-full sm:w-64" />
                        <flux:button type="submit">Cari</flux:button>
                    </div>
                </form>
            </div>

            {{-- Tabel Laporan --}}
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Tanggal</flux:table.column>
                    <flux:table.column>Pelapor</flux:table.column>
                    <flux:table.column>Lokasi & Koordinat</flux:table.column>
                    <flux:table.column>Foto</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($reports as $report)
                        <flux:table.row>
                            {{-- Tanggal --}}
                            <flux:table.cell>
                                <span class="text-sm font-medium">{{ $report->created_at->format('d M Y') }}</span><br>
                                <span class="text-xs text-zinc-500">{{ $report->created_at->format('H:i') }} WIB</span>
                            </flux:table.cell>

                            {{-- Pelapor --}}
                            <flux:table.cell>
                                <span class="font-bold">{{ $report->nama_pelapor }}</span><br>
                                <span class="text-xs text-zinc-500">{{ $report->kontak ?? 'Tidak ada kontak' }}</span>
                            </flux:table.cell>

                            {{-- Lokasi & Tombol Maps --}}
                            <flux:table.cell>
                                <p class="text-sm truncate max-w-50" title="{{ $report->lokasi_manual }}">
                                    {{ $report->lokasi_manual }}
                                </p>
                                @if($report->latitude && $report->longitude)
                                    <a href="https://maps.google.com/?q={{ $report->latitude }},{{ $report->longitude }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-800 hover:underline mt-1">
                                        <flux:icon.map-pin class="w-3 h-3" /> Buka di Maps
                                    </a>
                                @endif
                            </flux:table.cell>

                            {{-- Foto --}}
                            <flux:table.cell>
                                @if($report->foto_bukti)
                                    <a href="{{ asset('storage/' . $report->foto_bukti) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $report->foto_bukti) }}" alt="Foto Laporan"
                                            class="w-12 h-12 object-cover rounded-md border border-zinc-200 hover:opacity-75 transition">
                                    </a>
                                @else
                                    <span class="text-xs text-zinc-400">Tanpa Foto</span>
                                @endif
                            </flux:table.cell>

                            {{-- Status Badge --}}
                            <flux:table.cell>
                                @if($report->status === 'pending')
                                    <flux:badge color="red">Pending</flux:badge>
                                @elseif($report->status === 'proses')
                                    <flux:badge color="blue">Diproses</flux:badge>
                                @else
                                    <flux:badge color="green">Selesai</flux:badge>
                                @endif
                            </flux:table.cell>

                            {{-- Aksi (Detail & Ubah Status) --}}
                            <flux:table.cell class="flex gap-2">

                                <flux:modal.trigger name="update-status-{{ $report->id }}">
                                    <flux:button size="sm" variant="outline" icon="eye"
                                        onclick="loadMap({{ $report->id }}, {{ $report->latitude ?? 'null' }}, {{ $report->longitude ?? 'null' }})">
                                        Lihat & Proses</flux:button>
                                </flux:modal.trigger>

                                {{-- Modal All-in-One (Lebar disesuaikan agar lega) --}}
                                <flux:modal name="update-status-{{ $report->id }}" class="md:w-3xl">

                                    {{-- Header Modal --}}
                                    <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4 mb-6">
                                        <flux:heading size="lg">Detail Laporan
                                            #{{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</flux:heading>
                                        <flux:subheading>Dilaporkan pada {{ $report->created_at->format('d F Y - H:i') }}
                                            WIB</flux:subheading>
                                    </div>

                                    {{-- Isi Modal (Grid 2 Kolom) --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                        {{-- Kolom Kiri: Informasi Detail --}}
                                        <div class="space-y-4">
                                            <div>
                                                <span
                                                    class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Nama
                                                    Pelapor</span>
                                                <p class="font-bold text-zinc-900 dark:text-white mt-1">
                                                    {{ $report->nama_pelapor }}
                                                </p>
                                                <p class="text-sm text-zinc-500">{{ $report->kontak ?? 'Tanpa kontak' }}</p>
                                            </div>

                                            <div>
                                                <span
                                                    class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Deskripsi
                                                    Kejadian</span>
                                                <div
                                                    class="mt-1 p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg text-sm text-zinc-700 dark:text-zinc-300 border border-zinc-100 dark:border-zinc-700 max-h-32 overflow-y-auto">
                                                    {{ $report->deskripsi }}
                                                </div>
                                            </div>

                                            <div>
                                                <span
                                                    class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Lokasi
                                                    & Peta</span>
                                                <p class="text-sm text-zinc-900 dark:text-white mt-1 font-medium mb-3">
                                                    {{ $report->lokasi_manual }}
                                                </p>

                                                {{-- Container Peta dengan ID Unik --}}
                                                @if($report->latitude && $report->longitude)
                                                    <div id="map-container-{{ $report->id }}"
                                                        class="h-48 w-full rounded-lg border border-zinc-300 dark:border-zinc-700 z-10 mb-3">
                                                    </div>

                                                    <flux:button size="sm" variant="subtle" class="text-emerald-600 w-full"
                                                        href="https://maps.google.com/?q={{ $report->latitude }},{{ $report->longitude }}"
                                                        target="_blank">
                                                        <flux:icon.map-pin class="w-4 h-4 mr-2" /> Buka Navigasi Google Maps
                                                    </flux:button>
                                                @else
                                                    <div
                                                        class="p-4 bg-zinc-100 dark:bg-zinc-800 rounded-lg text-center text-xs text-zinc-500 border border-dashed border-zinc-300">
                                                        Pelapor tidak melampirkan titik koordinat.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Kolom Kanan: Foto & Form Status --}}
                                        <div class="space-y-6">
                                            {{-- Penampil Foto --}}
                                            <div>
                                                <span
                                                    class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Foto
                                                    Bukti</span>
                                                @if($report->foto_bukti)
                                                    <a href="{{ asset('storage/' . $report->foto_bukti) }}" target="_blank"
                                                        class="block mt-1 relative group">
                                                        <img src="{{ asset('storage/' . $report->foto_bukti) }}"
                                                            alt="Foto Laporan"
                                                            class="w-full h-40 object-cover rounded-lg border border-zinc-200 dark:border-zinc-700">
                                                        <div
                                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 rounded-lg transition flex items-center justify-center">
                                                            <span class="text-white text-sm font-medium">Klik untuk
                                                                perbesar</span>
                                                        </div>
                                                    </a>
                                                @else
                                                    <div
                                                        class="mt-1 w-full h-40 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center rounded-lg border border-dashed border-zinc-300">
                                                        <span class="text-zinc-400 text-sm">Tidak ada foto terlampir</span>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Form Kendali Status --}}
                                            <form action="{{ route('admin.pengaduan.status', $report->id) }}" method="POST"
                                                class="bg-emerald-50/50 dark:bg-emerald-950/20 p-4 rounded-xl border border-emerald-100 dark:border-emerald-900/50">
                                                @csrf
                                                @method('PATCH')

                                                <flux:select name="status" label="Tindak Lanjut (Ubah Status)">
                                                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>🔴 Pending</option>
                                                    <option value="proses" {{ $report->status == 'proses' ? 'selected' : '' }}>🔵 Diproses</option>
                                                    <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>🟢 Selesai</option>
                                                </flux:select>

                                                <div
                                                    class="flex justify-end gap-2 mt-4 pt-4 border-t border-emerald-200 dark:border-emerald-800">
                                                    <flux:modal.close>
                                                        <flux:button variant="ghost">Tutup</flux:button>
                                                    </flux:modal.close>
                                                    <flux:button type="submit" variant="primary">Simpan Status</flux:button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </flux:modal>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="6" class="text-center py-8 text-zinc-500">
                                @if($search)
                                    Tidak ada laporan yang sesuai dengan pencarian Anda.
                                @else
                                    Belum ada data pengaduan masuk.
                                @endif
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $reports->links() }}
            </div>
        </flux:card>
    </div>
    <script>
        // Menyimpan instance peta yang sedang aktif agar tidak double-render
        let activeMapInstances = {};

        function loadMap(reportId, lat, lng) {
            // Jika koordinat kosong (null), jangan lakukan apa-apa
            if (!lat || !lng) return;

            const mapContainerId = 'map-container-' + reportId;
            const container = document.getElementById(mapContainerId);

            if (!container) return;

            // Beri jeda 300ms agar animasi modal benar-benar selesai terbuka
            // Ini adalah kunci agar ukuran (width/height) container tidak 0
            setTimeout(() => {

                // Jika peta sudah pernah diload untuk laporan ini, cukup atur ulang ukurannya
                if (activeMapInstances[reportId]) {
                    activeMapInstances[reportId].invalidateSize();
                    return;
                }

                // Inisialisasi Peta Baru
                const map = L.map(mapContainerId, {
                    scrollWheelZoom: false // Mencegah scroll mouse ikut membesarkan peta
                }).setView([lat, lng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                // Tambahkan Pin
                L.marker([lat, lng]).addTo(map);

                // Simpan ke variabel global untuk mencegah error double initialization
                activeMapInstances[reportId] = map;

                // Perintah wajib Leaflet untuk menyesuaikan peta dengan modal yang baru muncul
                map.invalidateSize();

            }, 300); // 300ms delay
        }
    </script>
</x-layouts::app>