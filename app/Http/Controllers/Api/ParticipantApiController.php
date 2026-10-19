<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParticipantApiController extends Controller
{
    /**
     * Menampilkan daftar peserta dengan filter & pencarian.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Participant::query();

        if ($request->filled('q')) {
            $query->search($request->input('q'));
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Urutkan finisher berdasarkan chip_time tercepat, selebihnya urutkan nomor BIB
        $participants = $query->orderByRaw("CASE WHEN status = 'finished' AND chip_time IS NOT NULL THEN 0 ELSE 1 END")
            ->orderBy('chip_time', 'asc')
            ->orderBy('no_bib', 'asc')
            ->paginate($request->integer('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => $participants,
        ]);
    }

    /**
     * Mencari detail peserta tunggal berdasarkan Nomor BIB atau Kode Chip.
     */
    public function show(string $identifier): JsonResponse
    {
        $participant = Participant::where('no_bib', $identifier)
            ->orWhere('chip_code', $identifier)
            ->first();

        if (!$participant) {
            return response()->json([
                'success' => false,
                'message' => "Peserta dengan nomor BIB / Chip '{$identifier}' tidak ditemukan.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $participant,
        ]);
    }

    /**
     * Endpoint untuk Kiosk Offline-First menarik pembaruan delta data berkala.
     * Contoh: /api/v1/participants/delta?since=2026-10-19T22:00:00Z
     */
    public function delta(Request $request): JsonResponse
    {
        $since = $request->input('since');

        $query = Participant::query();

        if ($since) {
            try {
                $sinceTime = Carbon::parse($since);
                $query->where('updated_at', '>', $sinceTime);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format parameter since tidak valid. Gunakan format ISO 8601 (contoh: 2026-10-19T22:00:00Z).',
                ], 400);
            }
        }

        $records = $query->limit(500)->get();

        return response()->json([
            'success' => true,
            'count' => $records->count(),
            'server_time' => now()->toIso8601String(),
            'data' => $records,
        ]);
    }
}
