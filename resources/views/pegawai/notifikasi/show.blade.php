<x-layouts::pegawai_app :title="__('Detail Notifikasi')">

    <div class="mb-6">
        <flux:button href="{{ route('pegawai.notifikasi') }}" variant="subtle" icon="arrow-left" wire:navigate
            class="mb-4">
            Kembali
        </flux:button>
        <flux:heading size="xl" level="1">{{ $notification->title }}</flux:heading>
        <flux:subheading class="mt-1">Diterima pada: {{ $notification->created_at->format('d F Y - H:i') }} WIB
        </flux:subheading>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    {{-- Alert Sukses (Alpine.js) --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
            class="p-4 bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500 rounded-r-lg mb-6 flex items-center gap-2">
            <flux:icon.check-circle class="w-5 h-5" /> {{ session('success') }}
        </div>
    @endif

    {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6"> --}}

        {{-- Pesan Notifikasi (Kiri) --}}
        <div class="md:col-span-2 space-y-6 mb-6">
            <flux:card>
                <flux:heading size="lg" class="mb-4">Pesan Sistem</flux:heading>
                <div class="p-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap border border-zinc-200 dark:border-zinc-700">{{ $notification->message }}</div>
            </flux:card>
        </div>

        {{-- Panel Upload Dokumen SK (Kanan) --}}
        <div>
            <flux:card class="border-emerald-200 dark:border-emerald-900 shadow-sm">

                @if($notification->sk_file_path)
                    {{-- Tampilan Jika File SUDAH Diunggah --}}
                    <div class="text-center space-y-4">
                        <div
                            class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto">
                            <flux:icon.document-check class="w-8 h-8" />
                        </div>
                        <div>
                            <flux:heading size="lg" class="text-emerald-700">Berkas Diterima</flux:heading>
                            <p class="text-sm text-zinc-500 mt-1">Diserahkan pada
                                {{ $notification->submitted_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700 flex flex-col gap-2">
                            <flux:button href="{{ asset('storage/' . $notification->sk_file_path) }}" target="_blank"
                                variant="outline" class="w-full text-emerald-600 hover:text-emerald-700">
                                <flux:icon.eye class="w-4 h-4 mr-2" /> Lihat Dokumen
                            </flux:button>

                            {{-- Tombol Kecil untuk Upload Ulang (Opsional) --}}
                            <div x-data="{ showForm: false }" class="mt-2">
                                <button @click="showForm = !showForm" type="button"
                                    class="text-xs text-zinc-500 underline hover:text-zinc-700">Ada kesalahan? Unggah
                                    ulang</button>

                                <div x-show="showForm" x-transition class="mt-4 text-left">
                                    <form action="{{ route('pegawai.notifikasi.update', $notification->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf @method('PATCH')
                                        {{--
                                        <flux:input type="file" name="sk_file" accept=".pdf,image/*" required /> --}}
                                        {{-- <flux:input type="file" name="sk_file" label="Pilih File (PDF/JPG/PNG)"
                                            accept=".pdf,image/png,image/jpeg" required /> --}}
                                            <div
                                                class="p-4 border-2 border-dashed border-zinc-300 dark:border-zinc-600 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                                                <flux:input type="file" name="sk_file" label="Pilih File (PDF/JPG/PNG)"
                                                    accept=".pdf,image/png,image/jpeg" required />
                                                <p class="text-xs text-zinc-500 mt-2 flex gap-1 items-start">
                                                    <flux:icon.information-circle class="w-4 h-4 shrink-0" />
                                                    Maksimal ukuran file 5 MB.
                                                </p>
                                            </div>
                                        <flux:button type="submit" variant="primary" size="sm" class="mt-2 w-full">Update
                                            File</flux:button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- Tampilan Jika File BELUM Diunggah --}}
                    <div>
                        <flux:heading size="lg" class="mb-1">Unggah Dokumen</flux:heading>
                        <p class="text-xs text-zinc-500 mb-4">Mohon lampirkan berkas SK atau dokumen pendukung terkait
                            notifikasi ini.</p>

                        <form action="{{ route('pegawai.notifikasi.update', $notification->id) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div
                                class="p-4 border-2 border-dashed border-zinc-300 dark:border-zinc-600 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                                <flux:input type="file" name="sk_file" label="Pilih File (PDF/JPG/PNG)"
                                    accept=".pdf,image/png,image/jpeg" required />
                                <p class="text-xs text-zinc-500 mt-2 flex gap-1 items-start">
                                    <flux:icon.information-circle class="w-4 h-4 shrink-0" />
                                    Maksimal ukuran file 5 MB.
                                </p>
                            </div>

                            @error('sk_file')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror

                            <flux:button type="submit" variant="primary" class="w-full bg-emerald-600 hover:bg-emerald-700">
                                Unggah Berkas
                            </flux:button>
                        </form>
                    </div>
                @endif

            </flux:card>
        </div>
        {{--
    </div> --}}
    </x-layo uts::pegawai_app>