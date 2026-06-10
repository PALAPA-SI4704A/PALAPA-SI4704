<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->profile($request);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'users_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'users_name' => $request->users_name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function profile(Request $request)
    {
        $query = Report::where('user_id', Auth::id())->latest('report_id');

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('latitude', 'like', "%{$search}%")
                  ->orWhere('longitude', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('created_at', 'like', "%{$search}%");

                // Pencarian tanggal pintar (Bulan)
                $months = [
                    'januari' => 1, 'jan' => 1, 'january' => 1,
                    'februari' => 2, 'feb' => 2, 'february' => 2,
                    'maret' => 3, 'mar' => 3, 'march' => 3,
                    'april' => 4, 'apr' => 4,
                    'mei' => 5, 'may' => 5,
                    'juni' => 6, 'jun' => 6, 'june' => 6,
                    'juli' => 7, 'jul' => 7, 'july' => 7,
                    'agustus' => 8, 'agu' => 8, 'august' => 8, 'aug' => 8,
                    'september' => 9, 'sep' => 9,
                    'oktober' => 10, 'okt' => 10, 'october' => 10, 'oct' => 10,
                    'november' => 11, 'nov' => 11,
                    'desember' => 12, 'des' => 12, 'december' => 12, 'dec' => 12,
                ];

                $matchedMonths = [];
                foreach ($months as $name => $num) {
                    if (str_contains($name, $search)) {
                        $matchedMonths[] = $num;
                    }
                }
                
                if (!empty($matchedMonths)) {
                    $q->orWhereIn(DB::raw('MONTH(created_at)'), $matchedMonths);
                }

                // Pencarian Tahun atau Hari
                if (is_numeric($search)) {
                    if (strlen($search) == 4) {
                        $q->orWhereYear('created_at', $search);
                    } elseif (strlen($search) <= 2) {
                        $q->orWhereDay('created_at', $search)
                          ->orWhereMonth('created_at', $search);
                    }
                }
            });
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $reports = $query->get();
        $currentStatus = $request->status ?? 'semua';
        $currentSearch = $request->search ?? '';

        return view('profile', compact('reports', 'currentStatus', 'currentSearch'));
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
                'fire_level' => (string) $request->query('fire_level', ''),
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
                'fire_level' => $validated['fire_level'],
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

        $address = null;
        if (!empty($validated['latitude']) && !empty($validated['longitude'])) {
            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'User-Agent' => 'Palapa-App/1.0'
                ])->timeout(5)->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'json',
                    'lat' => $validated['latitude'],
                    'lon' => $validated['longitude'],
                ]);
                
                if ($response->successful()) {
                    $address = $response->json('display_name');
                }
            } catch (\Exception $e) {
                // Ignore failure
            }
        }

        $report = Report::create([
            'admin_id' => null,
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'photo' => $photoPath,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'address' => $address,
            'status' => 'pending',
            'fire_level' => $validated['fire_level'],
        ]);

        return redirect()->route('profile')->with('success', 'Laporan berhasil dikirim');
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
     * Show the status history of the specified resource (PLP-12 Mock Data).
     */
    public function history(Request $request, Report $report)
    {
        if (Auth::user()->role === 'masyarakat' && $report->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Ini bukan laporan Anda.');
        }

        $statusHistories = $report->statusHistories()->orderBy('id', 'asc')->get();

        if ($request->wantsJson() || $request->ajax()) {
            $statusHistories->each(function ($history) {
                $history->formatted_date = \Carbon\Carbon::parse($history->tanggal_ubah)->translatedFormat('d F Y, H:i');
            });
            return response()->json([
                'report' => $report,
                'statusHistories' => $statusHistories
            ]);
        }

        return response()
            ->view('reports.history', compact('report', 'statusHistories'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Report $report)
    {
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reports.edit', [
            'report' => $report,
            'prefill' => [
                'title' => $request->query('title') ?? $report->title,
                'description' => $request->query('description') ?? $report->description,
                'latitude' => $request->query('latitude') ?? $report->latitude,
                'longitude' => $request->query('longitude') ?? $report->longitude,
                'photo_temp' => $request->query('photo_temp') ?? '',
                'fire_level' => $request->query('fire_level') ?? $report->fire_level,
            ]
        ]);
    }

    public function previewEdit(StoreReportRequest $request, Report $report)
    {
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        $photoTempPath = $validated['photo_temp'] ?? null;
        if ($request->hasFile('photo')) {
            if ($photoTempPath && Storage::disk('public')->exists($photoTempPath)) {
                Storage::disk('public')->delete($photoTempPath);
            }
            $photoTempPath = $request->file('photo')->store('photos/tmp', 'public');
        }

        // If no photo_temp is provided and no new file, we can fall back to the existing report photo
        // in the view.

        return view('reports.preview', [
            'report' => $report,
            'data' => [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'photo_temp' => $photoTempPath,
                'fire_level' => $validated['fire_level'],
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreReportRequest $request, Report $report)
    {
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        $photoPath = $report->photo;
        if (!empty($validated['photo_temp']) && Storage::disk('public')->exists($validated['photo_temp'])) {
            $extension = pathinfo($validated['photo_temp'], PATHINFO_EXTENSION);
            $finalName = (string) Str::uuid() . ($extension ? '.' . $extension : '');
            $finalPath = 'photos/' . $finalName;

            Storage::disk('public')->move($validated['photo_temp'], $finalPath);
            
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            
            $photoPath = $finalPath;
        }

        $dataToUpdate = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'photo' => $photoPath,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'fire_level' => $validated['fire_level'],
        ];

        if ($report->latitude !== $validated['latitude'] || $report->longitude !== $validated['longitude']) {
            $address = null;
            if (!empty($validated['latitude']) && !empty($validated['longitude'])) {
                try {
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'User-Agent' => 'Palapa-App/1.0'
                    ])->timeout(5)->get('https://nominatim.openstreetmap.org/reverse', [
                        'format' => 'json',
                        'lat' => $validated['latitude'],
                        'lon' => $validated['longitude'],
                    ]);
                    
                    if ($response->successful()) {
                        $address = $response->json('display_name');
                    }
                } catch (\Exception $e) {
                    // Ignore failure
                }
            }
            $dataToUpdate['address'] = $address;
        }

        $report->update($dataToUpdate);

        return redirect()->route('profile')->with('success', 'Laporan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        ///
    }
}
