<x-layouts::app :title="__('Tambah Pegawai')">
    <div class="p-6 space-y-6">

        <flux:card>
            {{-- Header Form --}}
            <div class="mb-6">
                <flux:heading size="lg" class="text-zinc-700 dark:text-white">Form Tambah Pegawai</flux:heading>
                <p class="text-sm text-zinc-500 dark:text-white">Masukkan data biodata dan informasi jabatan pegawai
                    baru.</p>
            </div>

            {{-- Form Start --}}
            <form action="{{ route('pegawai.store') }}" method="POST" class="space-y-8">
                @csrf

                <fieldset>
                    <legend class="text-sm font-semibold text-zinc-700 dark:text-white mb-4 border-b pb-2 w-full">1.
                        Biodata Pribadi
                    </legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input name="nip" label="NIP" placeholder="Masukkan 18 digit NIP" value="{{ old('nip') }}"
                            minlength="18" maxlength="18" />

                        <flux:input name="name" label="Nama Lengkap (Beserta Gelar)" placeholder="Budi Santoso, S.Kom."
                            value="{{ old('name') }}" />

                        <flux:input type="date" name="birth_date" label="Tanggal Lahir" value="{{ old('birth_date') }}"
                            required />

                        <flux:select name="gender" label="Jenis Kelamin" required>
                            <option value="">Pilih Jenis Kelamin...</option>
                            <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>Laki-Laki
                            </option>
                            <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </flux:select>
                    </div>
                </fieldset>

                {{-- SECTION 3: Status & Jabatan --}}
                <fieldset>
                    <legend class="text-sm font-semibold text-zinc-700 dark:text-white mb-4 border-b pb-2 w-full">2.
                        Status &
                        Kepegawaian</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Dropdown Status --}}
                        <flux:select name="status" label="Status Pegawai" required>
                            <option value="">Pilih Status...</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonactive" {{ old('status') == 'nonactive' ? 'selected' : '' }}>Pensiun
                            </option>
                        </flux:select>

                        {{-- Dropdown Tipe --}}
                        <flux:select id="tipe_pegawai" name="type" label="Tipe Pegawai" required>
                            <option value="">Pilih Tipe...</option>
                            <option value="ASN" {{ old('type') == 'ASN' ? 'selected' : '' }}>ASN</option>
                            <option value="Non ASN" {{ old('type') == 'Non ASN' ? 'selected' : '' }}>Non ASN</option>
                        </flux:select>

                        <flux:input type="date" name="tmt_start" label="TMT Awal (Mulai Tugas)"
                            value="{{ old('tmt_start') }}" required />

                        <flux:input id="tmt_akhir" type="date" name="tmt_end"
                            label="TMT Akhir (Kosongkan jika aktif terus)" value="{{ old('tmt_end') }}" />

                        {{-- Foreign Keys --}}
                        {{-- Catatan: Pastikan Anda mengirim $grades, $ranks, dan $positions dari Controller --}}
                        <flux:select name="grade_id" label="Golongan">
                            <option value="">-- Tidak Ada / Belum Ditentukan --</option>
                            @isset($grades)
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->grade_code }}
                                    </option>
                                @endforeach
                            @endisset
                        </flux:select>

                        <flux:select name="rank_id" label="Pangkat">
                            <option value="">-- Tidak Ada / Belum Ditentukan --</option>
                            @isset($ranks)
                                @foreach($ranks as $rank)
                                    <option value="{{ $rank->id }}" {{ old('rank_id') == $rank->id ? 'selected' : '' }}>
                                        {{ $rank->rank_name }}
                                    </option>
                                @endforeach
                            @endisset
                        </flux:select>

                        <flux:select name="position_id" label="Jabatan">
                            <option value="">-- Tidak Ada / Belum Ditentukan --</option>
                            @isset($positions)
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>{{ $position->position_name }}</option>
                                @endforeach
                            @endisset
                        </flux:select>
                    </div>
                </fieldset>

                {{-- Footer / Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <flux:button href="{{ route('pegawai.index') }}" variant="subtle" wire:navigate>
                        Batal
                    </flux:button>

                    <flux:button type="submit" variant="primary" wire:navigate>
                        Simpan Pegawai
                    </flux:button>
                </div>

            </form>
        </flux:card>

    </div>
    <script>
        document.addEventListener('livewire:navigated', function () {
            console.log('Script loaded'); // Debug: Pastikan script ini dijalankan
            // Ambil elemen berdasarkan ID yang kita buat tadi
            const tipePegawai = document.getElementById('tipe_pegawai');
            const tmtAkhir = document.getElementById('tmt_akhir');

            // Fungsi untuk mengecek dan mengubah status input
            function toggleTmtAkhir() {
                if (tipePegawai.value === 'ASN') {
                    tmtAkhir.disabled = true;
                    tmtAkhir.value = '';
                } else {
                    tmtAkhir.disabled = false;
                }
            }

            // 1. Jalankan saat user mengganti pilihan dropdown
            tipePegawai.addEventListener('change', toggleTmtAkhir);

            // 2. Jalankan satu kali saat halaman pertama kali dimuat 
            // (Penting: agar saat user gagal validasi form dan halaman me-refresh, 
            // status disable tetap mengikuti pilihan old('type') sebelumnya).
            toggleTmtAkhir();
        });
    </script>
</x-layouts::app>