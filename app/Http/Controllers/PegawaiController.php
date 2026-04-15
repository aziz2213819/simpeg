<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class PegawaiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('pegawai.dashboard', compact('user'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('pegawai.settings.profile', compact('user'));
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
        ]);

        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()
            ->route('pegawai.profil')
            ->with('status', 'Email berhasil diperbarui.');
    }

    public function password()
    {
        $user = Auth::user();
        return view('pegawai.settings.password', compact('user'));
    }

    public function updatePassword(Request $request) {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()
            ],
        ], [
            'current_password.current_password' => 'Password lama yang Anda masukkan salah.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.mixed' => 'Password harus berupa huruf besar, huruf kecil, dan angka.',
            // 197002052003121004
        ]);
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        return back()->with('status', 'password-updated');
    }

    public function duafaktor() {
        $user = Auth::user();
        return view('pegawai.settings.two-factor', compact('user'));
    }

    public function enable2fa(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $enable($request->user());
        return back()->with('status', '2fa-enabling');
    }

    public function disable2fa(Request $request, DisableTwoFactorAuthentication $disable)
    {
        $disable($request->user());
        return back()->with('status', '2fa-disabled');
    }

    public function confirm2fa(Request $request, ConfirmTwoFactorAuthentication $confirmAction)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        try {
            $confirmAction($request->user(), $request->code);
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                'code' => 'Kode yang Anda masukkan tidak valid.',
            ]);
        }

        return back()->with('status', '2fa-confirmed');
    }

    public function tampilan()
    {
        $user = Auth::user();
        return view('pegawai.settings.tampilan', compact('user'));
    }

    

    public function notification()
    {
        $user = Auth::user();

        // Ambil notifikasi khusus untuk ID pegawai milik user ini
        // Urutkan dari yang paling baru (latest)
        $notifications = Notification::where('employee_id', $user->employee_id)
            ->latest()
            ->paginate(10);

        return view('pegawai.notifikasi.index', compact('user', 'notifications'));
    }

    public function notificationShow(Notification $notification)
    {
        // Keamanan: Pastikan notifikasi ini benar-benar milik pegawai yang login
        if ($notification->employee->id !== Auth::user()->employee->id) {
            abort(403, 'Anda tidak memiliki akses ke notifikasi ini.');
        }

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }
}
