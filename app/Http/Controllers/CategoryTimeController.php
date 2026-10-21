<?php

namespace App\Http\Controllers;

use App\Models\TimeSetting;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryTimeController extends Controller
{
    /**
     * Display the category time settings page.
     */
    public function index(): View
    {
        $categories = TimeSetting::orderBy('id', 'asc')->get();

        return view('category-times.index', compact('categories'));
    }

    /**
     * Store a newly created category time setting.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:100', 'unique:time_settings,kategori'],
            'gun_time' => ['required', 'date'],
            'fastest' => ['required', 'regex:/^(\d{1,2}:)?\d{2}:\d{2}$/'],
        ], [
            'kategori.required' => 'Nama kategori wajib diisi.',
            'kategori.unique' => 'Kategori ini sudah terdaftar.',
            'gun_time.required' => 'Gun Time wajib diisi.',
            'gun_time.date' => 'Format Gun Time tidak valid (contoh: 2026-10-20 11:46:26).',
            'fastest.required' => 'Fastest time wajib diisi.',
            'fastest.regex' => 'Format Fastest harus jam:menit:detik (contoh: 00:29:15 atau 29:15).',
        ]);

        $gunTime = Carbon::parse($validated['gun_time'])->format('Y-m-d H:i:s');
        $fastest = $this->normalizeTime($validated['fastest']);

        $category = TimeSetting::create([
            'kategori' => trim($validated['kategori']),
            'gun_time' => $gunTime,
            'fastest' => $fastest,
        ]);

        return redirect()->route('category-times.index')
            ->with('status', "Kategori '{$category->kategori}' berhasil ditambahkan ke database.");
    }

    /**
     * Update the specified category time setting.
     */
    public function update(Request $request, int|string $id): RedirectResponse
    {
        $timeSetting = TimeSetting::findOrFail($id);

        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:100', Rule::unique('time_settings', 'kategori')->ignore($timeSetting->id)],
            'gun_time' => ['required', 'date'],
            'fastest' => ['required', 'regex:/^(\d{1,2}:)?\d{2}:\d{2}$/'],
        ], [
            'kategori.required' => 'Nama kategori wajib diisi.',
            'kategori.unique' => 'Kategori ini sudah digunakan oleh data lain.',
            'gun_time.required' => 'Gun Time wajib diisi.',
            'gun_time.date' => 'Format Gun Time tidak valid (contoh: 2026-10-20 11:46:26).',
            'fastest.required' => 'Fastest time wajib diisi.',
            'fastest.regex' => 'Format Fastest harus jam:menit:detik (contoh: 00:29:15 atau 29:15).',
        ]);

        $gunTime = Carbon::parse($validated['gun_time'])->format('Y-m-d H:i:s');
        $fastest = $this->normalizeTime($validated['fastest']);

        $timeSetting->update([
            'kategori' => trim($validated['kategori']),
            'gun_time' => $gunTime,
            'fastest' => $fastest,
        ]);

        return redirect()->route('category-times.index')
            ->with('status', "Kategori '{$timeSetting->kategori}' berhasil diperbarui.");
    }

    /**
     * Remove the specified category time setting.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $timeSetting = TimeSetting::findOrFail($id);
        $kategoriName = $timeSetting->kategori;
        $timeSetting->delete();

        return redirect()->route('category-times.index')
            ->with('status', "Kategori '{$kategoriName}' berhasil dihapus dari database.");
    }

    /**
     * Helper to normalize time to HH:MM:SS
     */
    private function normalizeTime(string $time): string
    {
        $time = trim($time);
        $parts = explode(':', $time);

        if (count($parts) === 2) {
            return sprintf('00:%02d:%02d', (int) $parts[0], (int) $parts[1]);
        } elseif (count($parts) === 3) {
            return sprintf('%02d:%02d:%02d', (int) $parts[0], (int) $parts[1], (int) $parts[2]);
        }

        return $time;
    }
}
