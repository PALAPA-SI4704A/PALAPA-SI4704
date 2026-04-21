<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::where('user_id', Auth::id())
            ->latest('report_id')
            ->get();

        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('reports.create', [
            'prefill' => [
                'title' => (string) $request->query('title', ''),
                'description' => (string) $request->query('description', ''),
                'latitude' => (string) $request->query('latitude', ''),
                'longitude' => (string) $request->query('longitude', ''),
                'photo_temp' => (string) $request->query('photo_temp', ''),
            ],
        ]);
    }

    /**
     * Show a preview before persisting to database.
     */
    public function preview(StoreReportRequest $request)
    {
        $validated = $request->validated();

        $photoTempPath = $validated['photo_temp'] ?? null;
        if ($request->hasFile('photo')) {
            if ($photoTempPath && Storage::disk('public')->exists($photoTempPath)) {
                Storage::disk('public')->delete($photoTempPath);
            }
            $photoTempPath = $request->file('photo')->store('photos/tmp', 'public');
        }

        return view('reports.preview', [
            'data' => [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'photo_temp' => $photoTempPath,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();

        $photoPath = null;
        if (!empty($validated['photo_temp']) && Storage::disk('public')->exists($validated['photo_temp'])) {
            $extension = pathinfo($validated['photo_temp'], PATHINFO_EXTENSION);
            $finalName = (string) Str::uuid() . ($extension ? '.' . $extension : '');
            $finalPath = 'photos/' . $finalName;

            Storage::disk('public')->move($validated['photo_temp'], $finalPath);
            $photoPath = $finalPath;
        }

        Report::create([
            'admin_id' => null,
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'photo' => $photoPath,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'status' => 'pending',
        ]);

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dikirim');
    }

    /**
     * Serve uploaded report photos from the public disk.
     */
    public function photo(string $path): BinaryFileResponse
    {
        $normalizedPath = str_replace('\\', '/', ltrim($path, '/'));
        $normalizedPath = Str::startsWith($normalizedPath, 'public/')
            ? Str::after($normalizedPath, 'public/')
            : $normalizedPath;

        if (!Str::startsWith($normalizedPath, 'photos/')) {
            $normalizedPath = 'photos/' . ltrim($normalizedPath, '/');
        }

        if (!Storage::disk('public')->exists($normalizedPath)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($normalizedPath));
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
