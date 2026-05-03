<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Membuat rate limiter dengan nama 'pengaduan_sampah'
        RateLimiter::for('pengaduan_sampah', function (Request $request) {
            
            // Batasi 3 kali pengiriman form per 1 Jam berdasarkan IP Address.
            // Jika Anda ingin per menit, ganti menjadi Limit::perMinute(3)
            return Limit::perHour(3)->by($request->ip())->response(function (Request $request, array $headers) {
                
                // Pesan kustom jika user terkena limit (Mencegah error 429 bawaan yang kaku)
                // return response()->view('errors.429', [
                //     'message' => 'Anda telah mengirim terlalu banyak laporan. Demi mencegah spam, mohon tunggu beberapa saat sebelum melapor kembali.'
                // ], 429, $headers);
                
                // ATAU jika Anda ingin me-redirect kembali ke form dengan alert error:
                return redirect()->route('home')->with('error', 'Anda telah mengirim terlalu banyak laporan. Mohon tunggu 1 jam lagi untuk mencegah spam.');
            });
        });
    }
}
