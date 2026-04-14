<x-layouts::pegawai_app :title="__('Tampilan')">
    <section class="w-full">
        @include('partials.settings-heading')
        <x-pages::settings.pegawai_layout :heading="__('Tampilan')" :subheading="__('Perbarui tampilan pada akun anda')">
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
            </flux:radio.group>
        </x-pages::settings.pegawai_layout>
    </section>
</x-layouts::pegawai_app>