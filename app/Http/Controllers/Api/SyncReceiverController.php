<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\SyncLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SyncReceiverController extends Controller
{
    /**
     * Menerima push batch data peserta & chip time dari laptop lapangan/lokal.
     * Menggunakan Upsert (insert or update on duplicate no_bib) untuk efisiensi tinggi.
     */
    public function sync(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'nullable|string|max:64',
            'event_name' => 'nullable|string|max:150',
            'participants' => 'required|array|min:1',
            'participants.*.no_bib' => 'required|string|max:50',
            'participants.*.nama_lengkap' => 'required|string|max:255',
            'participants.*.chip_code' => 'nullable|string|max:50',
            'participants.*.gender' => 'nullable|string|max:20',
            'participants.*.kategori' => 'nullable|string|max:50',
            'participants.*.sub_kategori' => 'nullable|string|max:50',
            'participants.*.status' => 'nullable|string|in:finished,dns,dnf',
            'participants.*.pace' => 'nullable|string|max:20',
            'participants.*.gun_time' => 'nullable|string|max:30',
            'participants.*.chip_time' => 'nullable|string|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error on payload.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $batchId = $validated['batch_id'] ?? (string) Str::uuid();
        $eventName = $validated['event_name'] ?? 'Race Timing Sync';
        $participantsData = $validated['participants'];

        $now = now();
        $records = [];

        foreach ($participantsData as $item) {
            $records[] = [
                'no_bib' => trim((string) $item['no_bib']),
                'chip_code' => isset($item['chip_code']) ? trim((string) $item['chip_code']) : null,
                'nama_lengkap' => trim((string) $item['nama_lengkap']),
                'gender' => $item['gender'] ?? null,
                'kategori' => $item['kategori'] ?? null,
                'sub_kategori' => $item['sub_kategori'] ?? null,
                'status' => strtolower($item['status'] ?? 'dns'),
                'pace' => $item['pace'] ?? null,
                'gun_time' => $item['gun_time'] ?? null,
                'chip_time' => $item['chip_time'] ?? null,
                'synced_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::beginTransaction();
        try {
            // Upsert berdasarkan kolom 'no_bib' yang unik
            Participant::upsert(
                $records,
                ['no_bib'],
                [
                    'chip_code',
                    'nama_lengkap',
                    'gender',
                    'kategori',
                    'sub_kategori',
                    'status',
                    'pace',
                    'gun_time',
                    'chip_time',
                    'synced_at',
                    'updated_at',
                ]
            );

            // Catat log sinkronisasi
            SyncLog::create([
                'batch_id' => $batchId,
                'event_name' => $eventName,
                'records_count' => count($records),
                'ip_address' => $request->ip(),
                'status' => 'success',
                'message' => 'Batch successfully synced.',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data peserta berhasil disinkronisasi.',
                'batch_id' => $batchId,
                'synced_count' => count($records),
                'synced_at' => $now->toIso8601String(),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            // Log error jika transaksi gagal
            SyncLog::create([
                'batch_id' => $batchId,
                'event_name' => $eventName,
                'records_count' => count($records),
                'ip_address' => $request->ip(),
                'status' => 'failed',
                'message' => 'Sync failed: ' . $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses data ke database: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Memeriksa status kesehatan dan ringkasan sinkronisasi VPS.
     */
    public function status(): JsonResponse
    {
        $lastLog = SyncLog::latest()->first();
        $total = Participant::count();
        $finished = Participant::where('status', 'finished')->count();
        $dns = Participant::where('status', 'dns')->count();
        $dnf = Participant::where('status', 'dnf')->count();

        return response()->json([
            'success' => true,
            'server_time' => now()->toIso8601String(),
            'stats' => [
                'total_participants' => $total,
                'finished' => $finished,
                'dns' => $dns,
                'dnf' => $dnf,
            ],
            'last_sync' => $lastLog ? [
                'batch_id' => $lastLog->batch_id,
                'event_name' => $lastLog->event_name,
                'records_count' => $lastLog->records_count,
                'status' => $lastLog->status,
                'synced_at' => $lastLog->created_at->toIso8601String(),
            ] : null,
        ]);
    }
}
