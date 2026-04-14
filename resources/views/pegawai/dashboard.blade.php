<x-layouts::pegawai_app :title="__('Beranda')">
    <flux:heading size="xl" level="1">Selamat datang, {{ $user->employee->name }} ({{ $user->employee->type }})</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">Hari yang bahagia</flux:text>
    <flux:separator variant="subtle" />
</x-layouts::pegawai_app>
