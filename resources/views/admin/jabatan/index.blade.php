<x-layouts::app :title="__('Jabatan')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="p-6 space-y-6">

            <flux:card>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Data Jabatan</h2>

                    <flux:button wire:click="resetForm" x-on:click="$dispatch('open-modal', 'position-modal')">
                        Tambah Jabatan
                    </flux:button>
                </div>

                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Nama</flux:table.column>
                        <flux:table.column>Aksi</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach($positions as $position)
                            <flux:table.row>
                                <flux:table.cell>{{ $position->position_name }}</flux:table.cell>
                                <flux:table.cell class="flex gap-2">

                                    <flux:button size="sm" wire:click="edit({{ $position->id }})"
                                        x-on:click="$dispatch('open-modal', 'position-modal')">
                                        Edit
                                    </flux:button>

                                    <flux:button size="sm" variant="danger" wire:click="delete({{ $position->id }})"
                                        wire:confirm="Yakin ingin menghapus?">
                                        Hapus
                                    </flux:button>

                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>

                <div class="mt-4">
                    {{ $positions->links() }}
                </div>
            </flux:card>

            {{-- Modal --}}
            {{-- <flux:modal name="position-modal" class="md:w-1/2">

                <flux:heading size="lg">
                    {{ $isEdit ? 'Edit Jabatan' : 'Tambah Jabatan' }}
                </flux:heading>

                <div class="space-y-4 mt-4">

                    <flux:input label="Nama Jabatan" wire:model.defer="name" />

                    <flux:input label="Tipe Jabatan" wire:model.defer="type" />

                    <flux:textarea label="Deskripsi" wire:model.defer="description" />

                    <div class="flex justify-end gap-2 mt-4">
                        <flux:button variant="ghost" x-on:click="$dispatch('close-modal', 'position-modal')">
                            Batal
                        </flux:button>

                        @if($isEdit)
                        <flux:button wire:click="update" x-on:click="$dispatch('close-modal', 'position-modal')">
                            Update
                        </flux:button>
                        @else
                        <flux:button wire:click="store" x-on:click="$dispatch('close-modal', 'position-modal')">
                            Simpan
                        </flux:button>
                        @endif
                    </div>

                </div>

            </flux:modal> --}}

        </div>

    </div>
</x-layouts::app>