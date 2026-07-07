<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSchedule;

class AttendanceScheduleController extends Controller
{
    private function cekLogin()
    {
        if (!session('logged_in')) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu!');
        }
        if (!in_array(session('user_role'), ['admin', 'kepsek', 'superadmin'])) {
            return redirect('/')->with('error', 'Akses ditolak. Anda tidak memiliki izin ke halaman ini.');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->cekLogin()) return $redirect;

        $schedule = AttendanceSchedule::first() ?? new AttendanceSchedule();
        return view('attendance_schedule', compact('schedule'));
    }

    public function update(Request $request)
    {
        if ($redirect = $this->cekLogin()) return $redirect;

        $request->validate([
            'start_masuk' => 'required',
            'end_masuk' => 'required',
            'batas_terlambat' => 'required',
            'start_pulang' => 'required',
            'end_pulang' => 'required',
        ]);

        $schedule = AttendanceSchedule::first();
        if (!$schedule) {
            $schedule = new AttendanceSchedule();
        }

        $schedule->fill($request->all());
        $schedule->save();

        return back()->with('success', 'Pengaturan jadwal absen berhasil disimpan.');
    }
}
