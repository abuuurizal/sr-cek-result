<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\SyncLog;
use Illuminate\Http\Request;

class CekResultWebController extends Controller
{
    /**
     * Halaman Utama Portal Publik Cek Result
     */
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));
        $kategori = $request->input('kategori');

        $query = Participant::query();

        if (!empty($search)) {
            $query->search($search);
        }

        if (!empty($kategori)) {
            $query->where('kategori', $kategori);
        }

        $participants = $query->orderByRaw("CASE WHEN status = 'finished' AND chip_time IS NOT NULL THEN 0 ELSE 1 END")
            ->orderBy('chip_time', 'asc')
            ->orderBy('no_bib', 'asc')
            ->paginate(20)
            ->withQueryString();

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

        return view('welcome', compact('participants', 'categories', 'stats', 'lastSync', 'search', 'kategori'));
    }
}
