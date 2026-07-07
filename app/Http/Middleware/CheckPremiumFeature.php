<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPremiumFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        
        // Cek apakah ada fitur yang cocok dengan path (menu_code)
        $feature = \Illuminate\Support\Facades\DB::table('premium_features')
            ->where('menu_code', $path)
            ->first();

        // Jika rute ini diatur sebagai fitur premium dan belum di-unlock
        if ($feature && $feature->is_active && !$feature->is_unlocked) {
            
            // Check if demo is currently active
            $isDemoActive = false;
            if ($feature->has_demo && $feature->demo_expires_at) {
                if (\Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($feature->demo_expires_at))) {
                    $isDemoActive = true;
                }
            }

            if (!$isDemoActive) {
                // Jika role nya admin, redirect ke halaman premium_locked untuk bayar
                if (session('user_role') === 'admin') {
                    $payment = \Illuminate\Support\Facades\DB::table('payment_settings')->first();
                    return response()->view('premium_locked', compact('feature', 'payment'));
                }
                
                // Jika role nya murid, redirect ke halaman pemberitahuan
                if (session('user_role') === 'murid' || session('user_role') === 'siswa') {
                    return response()->view('premium_locked_siswa', compact('feature'));
                }
            }
        }

        return $next($request);
    }
}
