<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Grade;
use App\Models\Position;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $employees = Employee::query()
        ->when($search, function ($query, $search) {
            // Gunakan kurung pada query agar OR tidak merusak kondisi lain (jika ada)
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return view('admin.pegawai.index', compact('employees', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grades = Grade::all();
        $ranks = Rank::all();
        $positions = Position::all();
        return view('admin.pegawai.create', compact('grades', 'ranks', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Definisikan Aturan Validasi
        $rules = [
            // Validasi Akun (Users)
            // 'email'         => 'required|email|unique:users,email',
            // 'password'      => 'required|min:8',

            // Validasi Biodata (Employees)
            'nip'           => 'required|string|digits:18|unique:employees,nip',
            'name'          => 'required|string|max:255',
            'birth_date'    => 'required|date',
            'gender'        => 'required|in:l,p',
            'status'        => 'required|string',
            'tmt_start'     => 'required|date',
            'tmt_end'       => 'nullable|date|after_or_equal:tmt_start',
            'type'          => 'required|in:ASN,Non ASN',
            
            // Foreign Keys (Opsional tapi disarankan)
            'grade_id'      => 'nullable|exists:grades,id',
            'rank_id'       => 'nullable|exists:ranks,id',
            'position_id'   => 'nullable|exists:positions,id',
        ];

        // 2. Definisikan Pesan Bahasa Indonesia
        $messages = [
            'required'        => 'Kolom :attribute wajib diisi.',
            'email'           => 'Format :attribute tidak valid.',
            'unique'          => ':attribute ini sudah terdaftar di sistem.',
            'min'             => ':attribute minimal harus berisi :min karakter.',
            'max'             => ':attribute maksimal berisi :max karakter.',
            'digits'          => ':attribute harus berupa angka dan tepat :digits digit.',
            'date'            => 'Format :attribute harus berupa tanggal yang valid.',
            'after_or_equal'  => 'Tanggal Berakhir (TMT End) harus sama dengan atau setelah TMT Start.',
            'in'              => 'Pilihan :attribute tidak valid.',
            'exists'          => 'Data :attribute yang dipilih tidak ditemukan di database.',
        ];

        // 3. Ubah nama atribut agar lebih enak dibaca user (Opsional)
        $attributes = [
            'name'          => 'Nama Lengkap',
            'birth_date'    => 'Tanggal Lahir',
            'gender'        => 'Jenis Kelamin',
            'tmt_start'     => 'TMT Awal',
            'tmt_end'       => 'TMT Akhir',
            'type'          => 'Tipe Pegawai',
            'grade_id'      => 'Golongan',
            'rank_id'       => 'Pangkat',
            'position_id'   => 'Jabatan',
        ];

        // 4. Eksekusi Validasi
        $validatedData = $request->validate($rules, $messages, $attributes);

        // 5. Simpan Data (Gunakan DB Transaction karena ada 2 tabel)
        DB::transaction(function () use ($validatedData) {
            $employee = Employee::create([
                'nip'           => $validatedData['nip'],
                'name'          => $validatedData['name'],
                'birth_date'    => $validatedData['birth_date'],
                'gender'        => $validatedData['gender'],
                'status'        => $validatedData['status'],
                'tmt_start'     => $validatedData['tmt_start'],
                'tmt_end'       => $validatedData['tmt_end'] ?? null,
                'type'          => $validatedData['type'],
                'grade_id'      => $validatedData['grade_id'] ?? null,
                'rank_id'       => $validatedData['rank_id'] ?? null,
                'position_id'   => $validatedData['position_id'] ?? null,
            ]);

            User::create([
                'employee_id'   => $employee->id,
                'email'         => "{$employee->nip}@email.com",
                'password'      => bcrypt($employee->nip),
            ]);
        });

        return redirect()->route('pegawai.index')->with('success', 'Data Pegawai dan Akun berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $pegawai)
    {
        return view('pegawai.show', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $pegawai)
    {
        $grades = Grade::all();
        $ranks = Rank::all();
        $positions = Position::all();   
        return view('admin.pegawai.edit', compact('pegawai', 'grades', 'ranks', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $pegawai)
    {
        $rules = [
            // 'email'         => 'required|email|unique:users,email,' . ($user ? $user->id : ''),
            // 'password'      => 'nullable|min:8',
            'nip'           => 'required|string|digits:18|unique:employees,nip,' . $pegawai->id,
            'name'          => 'required|string|max:255',
            'birth_date'    => 'required|date',
            'gender'        => 'required|in:l,p',
            'status'        => 'required|string',
            'tmt_start'     => 'required|date',
            'tmt_end'       => 'nullable|date|after_or_equal:tmt_start',
            'type'          => 'required|string',
            'grade_id'      => 'nullable|exists:grades,id',
            'rank_id'       => 'nullable|exists:ranks,id',
            'position_id'   => 'nullable|exists:positions,id',
        ];

        $messages = [
            'required'        => 'Kolom :attribute wajib diisi.',
            'unique'          => ':attribute ini sudah dipakai orang lain.',
            'after_or_equal'  => 'TMT Akhir harus sama dengan atau setelah TMT Awal.',
            'min'             => ':attribute minimal :min karakter.',
            'in'              => 'Pilihan pada kolom :attribute tidak valid.',
        ];

        $validatedData = $request->validate($rules, $messages);

        DB::transaction(function () use ($validatedData, $pegawai) {
            // 1. Update Biodata Pegawai
            $pegawai->update([
                'nip'           => $validatedData['nip'],
                'name'          => $validatedData['name'],
                'birth_date'    => $validatedData['birth_date'],
                'gender'        => $validatedData['gender'],
                'status'        => $validatedData['status'],
                'tmt_start'     => $validatedData['tmt_start'],
                'tmt_end'       => $validatedData['tmt_end'] ?? null,
                'type'          => $validatedData['type'],
                'grade_id'      => $validatedData['grade_id'] ?? null,
                'rank_id'       => $validatedData['rank_id'] ?? null,
                'position_id'   => $validatedData['position_id'] ?? null,
            ]);
        });

        return redirect()->route('pegawai.index')->with('success', 'Data Pegawai berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $pegawai)
    {
        // dd($pegawai);
        $pegawai->delete();

        return redirect()->route('pegawai.index')
                         ->with('success', 'Data pegawai berhasil dihapus.');
    }
}
