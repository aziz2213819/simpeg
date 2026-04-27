<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- Header Ringkas --}}
        <div>
            <flux:heading size="xl">Dashboard Admin</flux:heading>
            @if (auth()->user()->role == "admin_simpeg")
                <flux:subheading>Ringkasan sistem kepegawaian hari ini.</flux:subheading>
            @elseif (auth()->user()->role == "admin_sampah")
                <flux:subheading>Ringkasan sistem laporan masyarakat hari ini.</flux:subheading>
            @endif
        </div>

        {{-- @if (auth()->user()->role == "admin_simpeg") --}}
        {{-- TOP SECTION: 3 Stat Cards --}}
        <div class="grid gap-4 md:grid-cols-3">

            {{-- Card 1: Laporan Pending --}}
            <flux:card class="flex items-center gap-4 p-6! border-l-4 border-l-red-500">
                <div class="bg-red-100 text-red-600 p-3 rounded-full dark:bg-red-900/30 dark:text-red-400">
                    <flux:icon.bell-alert class="w-8 h-8" />
                </div>
                <div>
                    <flux:subheading>Laporan Pending</flux:subheading>
                    <flux:heading size="xl">{{ $pendingReportsCount }} <span
                            class="text-sm font-normal text-zinc-500 dark:text-zinc-400">Laporan</span></flux:heading>
                </div>
            </flux:card>

            {{-- Card 2: Pegawai ASN --}}
            <flux:card class="flex items-center gap-4 p-6! border-l-4 border-l-emerald-500">
                <div
                    class="bg-emerald-100 text-emerald-600 p-3 rounded-full dark:bg-emerald-900/30 dark:text-emerald-400">
                    <flux:icon.user-group class="w-8 h-8" />
                </div>
                <div>
                    <flux:subheading>Jumlah Laporan</flux:subheading>
                    <flux:heading size="xl">{{ $allReport }} <span
                            class="text-sm font-normal text-zinc-500 dark:text-zinc-400">Jumlah</span></flux:heading>
                </div>
            </flux:card>

            {{-- Card 3: Pegawai Non ASN --}}
            <flux:card class="flex items-center gap-4 p-6! border-l-4 border-l-blue-500">
                <div class="bg-blue-100 text-blue-600 p-3 rounded-full dark:bg-blue-900/30 dark:text-blue-400">
                    <flux:icon.users class="w-8 h-8" />
                </div>
                <div>
                    <flux:subheading>Pegawai Non ASN</flux:subheading>
                    <flux:heading size="xl">{{ $nonAsnCount }} <span
                            class="text-sm font-normal text-zinc-500 dark:text-zinc-400">Orang</span></flux:heading>
                </div>
            </flux:card>

        </div>
        {{-- @elseif () --}}
        {{-- @endif --}}

        {{-- BOTTOM SECTION: Tabel Laporan Terbaru --}}
        <flux:card class="relative flex-1 flex flex-col">
            <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-4 mb-4">
                <div>
                    <flux:heading size="lg">Laporan Masyarakat Masuk</flux:heading>
                    <flux:subheading>Segera tindak lanjuti laporan yang berstatus pending di bawah ini.
                    </flux:subheading>
                </div>
                <flux:button size="sm" href="{{ route('admin.pengaduan.index') }}" variant="outline" wire:navigate>
                    Lihat Semua
                </flux:button>
            </div>

            <div class="overflow-x-auto">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Tanggal</flux:table.column>
                        <flux:table.column>Pelapor</flux:table.column>
                        <flux:table.column>Deskripsi Singkat</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse($recentReports as $report)
                            <flux:table.row>
                                <flux:table.cell>
                                    <span class="font-medium">{{ $report->created_at->format('d M Y') }}</span><br>
                                    <span class="text-xs text-zinc-500">{{ $report->created_at->format('H:i') }} WIB</span>
                                </flux:table.cell>

                                <flux:table.cell>
                                    <span class="font-bold">{{ $report->nama_pelapor }}</span>
                                </flux:table.cell>

                                <flux:table.cell>
                                    <p class="truncate max-w-xs text-sm text-zinc-600 dark:text-zinc-400"
                                        title="{{ $report->deskripsi }}">
                                        {{ $report->deskripsi }}
                                    </p>
                                </flux:table.cell>

                                <flux:table.cell>
                                    <flux:badge color="red">Pending</flux:badge>
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="4" class="text-center py-8 text-zinc-500">
                                    <flux:icon.check-circle class="w-8 h-8 mx-auto text-emerald-500 mb-2" />
                                    Bagus! Tidak ada laporan masyarakat yang tertunda.
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>
        </flux:card>

    </div>
</x-layouts::app>