<x-layouts::app :title="__('Edit Notifikasi')">
    <div class="p-6 space-y-6">
        <flux:card>
            <div class="mb-6">
                <flux:heading size="lg">Edit Pesan Notifikasi</flux:heading>
            </div>

            <form action="{{ route('notifikasi.update', $notifikasi->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <flux:select name="employee_id" label="Pilih Pegawai Penerima" required>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id', $notifikasi->employee_id) == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </flux:select>

                <flux:select name="type" label="Tipe Notifikasi (Kategori)" required>
                    <option value="pangkat" {{ old('type', $notifikasi->type) == 'pangkat' ? 'selected' : '' }}>Kenaikan
                        Pangkat</option>
                    <option value="gaji_berkala" {{ old('type', $notifikasi->type) == 'gaji_berkala' ? 'selected' : '' }}>
                        Kenaikan Gaji Berkala</option>
                    <option value="pensiun" {{ old('type', $notifikasi->type) == 'pensiun' ? 'selected' : '' }}>Persiapan
                        Pensiun</option>
                    <option value="pengumuman" {{ old('type', $notifikasi->type) == 'pengumuman' ? 'selected' : '' }}>
                        Pengumuman Umum</option>
                    <option value="peringatan" {{ old('type', $notifikasi->type) == 'peringatan' ? 'selected' : '' }}>
                        Peringatan / SP</option>
                </flux:select>

                <flux:input name="title" label="Judul Pesan" value="{{ old('title', $notifikasi->title) }}" required />

                <flux:textarea name="message" label="Isi Pesan Detail" rows="4" required>
                    {{ old('message', $notifikasi->message) }}</flux:textarea>

                {{-- Status Dibaca (Bisa direset oleh Admin) --}}
                <flux:select name="is_read" label="Status Keterbacaan Oleh Pegawai" required>
                    <option value="0" {{ old('is_read', $notifikasi->is_read) == 0 ? 'selected' : '' }}>Belum Dibaca
                    </option>
                    <option value="1" {{ old('is_read', $notifikasi->is_read) == 1 ? 'selected' : '' }}>Sudah Dibaca
                    </option>
                </flux:select>

                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <flux:button href="{{ route('notifikasi.index') }}" variant="subtle" wire:navigate>Batal</flux:button>
                    <flux:button type="submit" variant="primary" color="emerald">Simpan Perubahan</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>