<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidateDeviceToken
{
    /**
     * Konfigurasi Rate Limiting per endpoint.
     * Key => [max_request, window_seconds]
     */
    private array $limits = [
        'presensi'          => [30, 60],   // max 30 req / menit
        'cek-status-alat'   => [20, 60],   // max 20 req / menit (polling tiap 5s = 12/mnt normal)
        'konfirmasi-enroll' => [10, 60],   // max 10 req / menit
        'konfirmasi-hapus'  => [10, 60],   // max 10 req / menit
        'default'           => [30, 60],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // 1. ─── Validasi device_token ────────────────────────────────────
        $token = $request->input('device_token') ?? $request->header('X-Device-Token');

        if (!$token) {
            Log::warning('[ESP32 API] Request tanpa device_token', [
                'ip'  => $request->ip(),
                'url' => $request->fullUrl(),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Device token wajib disertakan.',
            ], 401);
        }

        // Cek token di database
        $device = DB::table('devices')->where('device_token', $token)->first();

        if (!$device) {
            Log::warning('[ESP32 API] Token tidak dikenali', [
                'token' => substr($token, 0, 8) . '****',
                'ip'    => $request->ip(),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Device token tidak valid.',
            ], 403);
        }

        // 2. ─── Rate Limiting ─────────────────────────────────────────────
        // Identifikasi endpoint
        $segment = $request->segment(2) ?? 'default'; // /api/presensi → segment 2 = presensi
        [$maxReq, $window] = $this->limits[$segment] ?? $this->limits['default'];

        // Cache key: gabungan token + endpoint
        $cacheKey = 'esp32_rl:' . md5($token) . ':' . $segment;
        $current  = Cache::get($cacheKey, 0);

        if ($current >= $maxReq) {
            Log::warning('[ESP32 API] Rate limit terlampaui', [
                'device'   => $device->nama_alat,
                'endpoint' => $segment,
                'count'    => $current,
                'ip'       => $request->ip(),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => "Terlalu banyak request. Coba lagi dalam {$window} detik.",
            ], 429);
        }

        // Tambah counter
        if ($current === 0) {
            Cache::put($cacheKey, 1, $window);
        } else {
            Cache::increment($cacheKey);
        }

        // 3. ─── Sisipkan info device ke request ───────────────────────────
        $request->merge(['_device' => $device]);

        return $next($request);
    }
}
