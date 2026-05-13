<x-layouts::app.landing :title="__('Profil Lengkap DLH')">
    <x-navbar />

    <div class="w-full min-h-screen text-zinc-900 dark:text-zinc-100 pt-12">
        <x-heading-dlh />
        
        {{-- 1. HERO & TENTANG KAMI --}}
        <section class="pb-12 px-6 max-w-5xl mx-auto text-center border-b border-zinc-100 dark:border-zinc-800">
            <h1 class="text-xl md:text-2xl font-black uppercase tracking-tighter mt-2 mb-8">
                Profil Instansi
            </h1>
            <div class="bg-emerald-50 dark:bg-emerald-950/30 p-8 rounded-3xl border border-emerald-100 dark:border-emerald-800 text-left">
                <h2 class="text-xl font-black uppercase text-emerald-700 dark:text-emerald-400 mb-4 flex items-center gap-2">
                    <span class="w-8 h-1 bg-emerald-600"></span> Tentang Kami
                </h2>
                <p class="text-md leading-relaxed font-medium">
                    Dinas Lingkungan Hidup (DLH) Kabupaten Bangkalan adalah instansi pemerintah yang bertanggung jawab
                    penuh dalam pengawasan kualitas lingkungan, pengelolaan sampah, serta pemeliharaan keindahan kota. 
                    Kami bergerak sebagai garda terdepan dalam menjaga ekosistem Bangkalan demi generasi masa depan.
                </p>
            </div>
        </section>

        {{-- 2. VISI & MISI --}}
        <section class="py-20 px-6 max-w-6xl mx-auto grid md:grid-cols-2 gap-12">
            {{-- VISI --}}
            <div class="space-y-6">
                <h2 class="text-3xl font-black uppercase border-b-4 border-emerald-600 inline-block">Visi</h2>
                <div class="bg-zinc-900 text-white p-8 rounded-2xl shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                        </svg>
                    </div>
                    <p class="text-md font-bold italic leading-relaxed relative z-10">
                        "Terwujudnya Lingkungan Hidup yang Berkualitas, Bersih, dan Berkelanjutan Menuju Bangkalan yang Sejahtera."
                    </p>
                </div>
            </div>

            {{-- MISI --}}
            <div class="space-y-6">
                <h2 class="text-3xl font-black uppercase border-b-4 border-emerald-600 inline-block">Misi</h2>
                <ul class="space-y-4">
                    @foreach([
                        'Meningkatkan pengawasan dan pengendalian pencemaran lingkungan.',
                        'Mengoptimalkan sistem pengelolaan sampah berbasis masyarakat.',
                        'Meningkatkan kualitas ruang terbuka hijau dan keanekaragaman hayati.',
                        'Mendorong partisipasi aktif masyarakat dalam pelestarian lingkungan.'
                    ] as $index => $misi)
                        <li class="flex gap-4 items-start bg-white dark:bg-zinc-800 p-4 rounded-xl border border-zinc-100 dark:border-zinc-700 shadow-sm">
                            <span class="bg-emerald-600 text-white font-black px-3 py-1 rounded-lg text-sm">{{ $index + 1 }}</span>
                            <p class="font-bold text-zinc-700 dark:text-zinc-300">{{ $misi }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- 3. TUJUAN --}}
        <section class="py-20 bg-zinc-50 dark:bg-zinc-800/50">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 class="text-3xl font-black uppercase mb-12">Tujuan Strategis</h2>
                <div class="grid md:grid-cols-2 gap-6 text-left">
                    <div class="p-6 bg-white dark:bg-zinc-900 rounded-2xl border-t-4 border-emerald-600 shadow-md">
                        <p class="font-bold text-zinc-600 dark:text-zinc-400">Menurunkan tingkat beban pencemaran pada air, udara, dan tanah di wilayah Kabupaten Bangkalan secara signifikan.</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-zinc-900 rounded-2xl border-t-4 border-emerald-600 shadow-md">
                        <p class="font-bold text-zinc-600 dark:text-zinc-400">Menciptakan lingkungan perkotaan yang asri, teduh, dan bebas dari tumpukan sampah liar.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- 4. STRUKTUR ORGANISASI (UPLOAD OTOMATIS) --}}
        <section class="py-20 px-6">
            <div class="max-w-7xl mx-auto flex flex-col items-center">
                <h2 class="text-3xl font-black mb-16 text-center uppercase tracking-tight">Struktur Organisasi</h2>
                
                <div class="w-full flex justify-center">
                    @if($struktural && $struktural->photo_path)
                        <div class="relative group max-w-5xl w-full flex flex-col items-center">
                            <div class="overflow-hidden rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50 p-2">
                                <img 
                                    src="{{ asset('storage/' . $struktural->photo_path) }}" 
                                    alt="Struktur Organisasi DLH" 
                                    class="w-full h-auto rounded-xl object-contain"
                                    onerror="this.src='https://placehold.co/1200x800?text=Gambar+Struktur+Tidak+Ditemukan'"
                                >
                            </div>
                            
                            @if($struktural->keterangan)
                                <div class="mt-8 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900 rounded-2xl">
                                    <p class="text-center text-sm md:text-md text-emerald-800 dark:text-emerald-400 font-medium italic">
                                        "{{ $struktural->keterangan }}"
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- Tampilan jika data gambar belum diupload di admin --}}
                        <div class="flex flex-col items-center justify-center py-24 border-4 border-dashed border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-3xl">
                            <svg class="w-20 h-20 text-zinc-300 dark:text-zinc-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-zinc-500 font-bold text-lg">Bagan struktur organisasi belum tersedia.</p>
                            <p class="text-zinc-400 text-sm mt-1">Silakan unggah melalui panel admin.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <x-footer />
</x-layouts::app.landing>