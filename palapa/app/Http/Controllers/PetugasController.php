<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    public function index()
    {
        $laporanHariIni = Report::whereDate('created_at', today())->count();
        $diproses = Report::where('status', 'diproses')->count();
        $selesai = Report::where('status', 'selesai')->count();
        $total = Report::count();

        $laporanMasuk = Report::orderBy('created_at', 'desc')->get();
        $petugasTersedia = User::where('role', 'petugas')->get();

        return view('petugas.dashboard', compact('laporanHariIni', 'diproses', 'selesai', 'total', 'laporanMasuk', 'petugasTersedia'));
    }

    public function show(Report $report)
    {
        $petugasTersedia = User::where('role', 'petugas')->get();
        return view('petugas.reports.show', compact('report', 'petugasTersedia'));
    }

    public function assign(Request $request, Report $report, User $petugas)
    {
        if ($petugas->role !== 'petugas') {
            abort(403);
        }

        Penugasan::create([
            'report_id' => $report->report_id,
            'petugas_id' => $petugas->users_id,
            'assigned_at' => now(),
        ]);

        $report->update(['status' => 'diproses']);

        return redirect()->back()->with('success', 'Petugas berhasil ditugaskan.');
    }
}
