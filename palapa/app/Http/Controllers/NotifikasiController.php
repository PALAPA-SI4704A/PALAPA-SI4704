<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil notifikasi milik user yang sedang login, diurutkan dari yang terbaru
        $notifikasis = Notifikasi::where('user_id', Auth::id())
                                 ->orderBy('notifikasi_id', 'desc')
                                 ->get();

        return view('notifikasi.index', compact('notifikasis'));
    }

    /**
     * Method khusus untuk membuat data dummy (Simulasi PBI 15)
     */
    public function buatDummy()
    {
        Notifikasi::create([
            'user_id' => Auth::id(),
            'pesan' => 'Status laporan Anda #' . rand(100, 999) . ' telah diperbarui menjadi: Diproses.',
            'is_read' => 0,
        ]);

        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi dummy berhasil ditambahkan!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Notifikasi $notifikasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notifikasi $notifikasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notifikasi $notifikasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notifikasi $notifikasi)
    {
        //
    }
}