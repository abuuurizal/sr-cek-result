<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\SyncLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CekResultWebController extends Controller
{
    /**
     * Halaman Utama Portal Publik Cek Result
     */
    public function index(Request $request): View
    {
        $hasSearched = $request->has('q') || $request->has('kategori');
        $search = trim($request->input('q', ''));
        $kategori = $request->input('kategori');

        $participants = collect();

        if ($hasSearched) {
            $query = Participant::query();

            if (! empty($search)) {
                $query->search($search);
            }

            if (! empty($kategori)) {
                $query->where('kategori', $kategori);
            }

            $participants = $query->orderByRaw("CASE WHEN status = 'finished' AND chip_time IS NOT NULL THEN 0 ELSE 1 END")
                ->orderBy('chip_time', 'asc')
                ->orderBy('no_bib', 'asc')
                ->take(10)
                ->get();
        }

        // Kategori unik untuk filter dropdown
        $categories = Participant::whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori');

        // Statistik Cepat
        $stats = [
            'total' => Participant::count(),
            'finished' => Participant::where('status', 'finished')->count(),
            'dns' => Participant::where('status', 'dns')->count(),
            'dnf' => Participant::where('status', 'dnf')->count(),
        ];

        $lastSync = SyncLog::latest()->first();
        $systemTimes = $this->getSystemTimesData();

        return view('welcome', compact('participants', 'categories', 'stats', 'lastSync', 'search', 'kategori', 'systemTimes', 'hasSearched'));
    }

    /**
     * Memperbarui data peserta dari modal edit web
     */
    public function updateParticipant(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $participant = Participant::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'gender' => 'nullable|string|max:20',
            'status' => 'required|string|in:finished,dns,dnf',
            'pace' => 'nullable|string|max:20',
            'gun_time' => 'nullable|string|max:30',
            'chip_time' => 'nullable|string|max:30',
        ]);

        $now = now();

        $participant->update([
            'nama_lengkap' => trim($validated['nama_lengkap']),
            'gender' => ! empty($validated['gender']) ? trim($validated['gender']) : null,
            'status' => strtolower($validated['status']),
            'pace' => ! empty($validated['pace']) ? trim($validated['pace']) : null,
            'gun_time' => ! empty($validated['gun_time']) ? trim($validated['gun_time']) : null,
            'chip_time' => ! empty($validated['chip_time']) ? trim($validated['chip_time']) : null,
            'synced_at' => $now,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data peserta berhasil diperbarui.',
                'participant' => $participant->fresh(),
            ]);
        }

        return back()->with('success', 'Data peserta berhasil diperbarui.');
    }

    /**
     * Endpoint API JSON untuk sinkronisasi info waktu
     */
    public function systemTime(): JsonResponse
    {
        return response()->json($this->getSystemTimesData());
    }

    /**
     * Mengambil info waktu Server (PHP) dan Database (MySQL/SQLite)
     *
     * @return array<string, mixed>
     */
    public function getSystemTimesData(): array
    {
        $serverNow = now();
        $dbTime = null;
        $dbTz = null;
        $dbStatus = 'connected';
        $dbDriver = 'unknown';

        try {
            $dbDriver = DB::connection()->getDriverName();
            if ($dbDriver === 'sqlite') {
                $raw = DB::select("SELECT datetime('now', 'localtime') as t")[0]->t ?? null;
                $dbTime = $raw;
                $dbTz = 'Localtime (SQLite)';
            } else {
                $raw = DB::select('SELECT NOW() as t, @@system_time_zone as sys_tz, @@time_zone as tz')[0] ?? null;
                $dbTime = $raw->t ?? null;
                $dbTz = ($raw->tz ?? '') === 'SYSTEM' ? ($raw->sys_tz ?? 'SYSTEM') : ($raw->tz ?? 'UTC');
            }
        } catch (\Throwable $e) {
            $dbDriver = config('database.default');
            $dbStatus = 'Error: '.$e->getMessage();
        }

        $defaultConn = config('database.default');

        return [
            'server' => [
                'time' => $serverNow->format('H:i:s'),
                'full' => $serverNow->format('Y-m-d H:i:s'),
                'timezone' => config('app.timezone', 'UTC').' (UTC'.$serverNow->format('P').')',
            ],
            'database' => [
                'time' => $dbTime ? date('H:i:s', strtotime($dbTime)) : '--:--:--',
                'full' => $dbTime ?? 'Tidak terhubung',
                'timezone' => $dbTz ?? '-',
                'driver' => strtoupper((string) $dbDriver),
                'host' => config("database.connections.{$defaultConn}.host", '127.0.0.1'),
                'database' => config("database.connections.{$defaultConn}.database", '-'),
                'status' => $dbStatus,
            ],
        ];
    }
}
