<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Struktural;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StrukturalController extends Controller
{
    public function index(Request $request)
    {
        $strukturals = Struktural::latest()->get();
        return view('admin.struktural.index', compact('strukturals'));
    }

    public function create()
    {
        return view('admin.struktural.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo_path' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ],
        [
            'photo_path.required' => 'Foto struktural harus diunggah.',
            'photo_path.image' => 'File yang diunggah harus berupa gambar.',
            'photo_path.mimes' => 'Format gambar yang diizinkan adalah JPEG, PNG, dan JPG.',
            'photo_path.max' => 'Ukuran file gambar tidak boleh lebih dari 2 MB.',
        ],
        [
            'photo_path' => 'Foto Struktural',
        ]);

        $path = $request->file('photo_path')->store('strukturals', 'public');

        $isFirst = Struktural::count() === 0;
        // dd($path);
        Struktural::create([
            'photo_path' => $path,
            'is_active' => $isFirst ? true : false,
        ]);

        return redirect()->route('struktural.index')->with('success', 'Foto struktural berhasil diunggah.');
    }

    public function activate(Struktural $struktural)
    {
        Struktural::query()->update(['is_active' => false]);
        $struktural->update(['is_active' => true]);
        return redirect()->back()->with('success', 'Foto struktural utama berhasil diubah.');
    }

    public function show(Report $pengaduan)
    {
        
    }

    public function destroy(Struktural $struktural)
    {
        // Jangan izinkan hapus jika sedang aktif
        if ($struktural->is_active) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus foto yang sedang aktif digunakan.');
        }

        // UBAH 'foto' MENJADI 'photo_path' DI SINI
        if ($struktural->photo_path && Storage::disk('public')->exists($struktural->photo_path)) {
            Storage::disk('public')->delete($struktural->photo_path);
        }

        $struktural->delete();

        return redirect()->back()->with('success', 'Foto berhasil dihapus.');
    }
}