<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('paticipants')) {
            // 1. Bersihkan baris duplikat yang terlanjur tersimpan di VPS
            // Cari nomor BIB yang duplikat
            $duplicates = DB::table('paticipants')
                ->select('no_bib', DB::raw('COUNT(*) as total'))
                ->whereNotNull('no_bib')
                ->groupBy('no_bib')
                ->having('total', '>', 1)
                ->pluck('no_bib');

            foreach ($duplicates as $bib) {
                // Ambil semua row dengan nomor BIB tersebut
                // Prioritaskan yang statusnya 'finished', lalu 'dnf', lalu yang ID-nya paling baru
                $rows = DB::table('paticipants')
                    ->where('no_bib', $bib)
                    ->orderByRaw("CASE WHEN status = 'finished' THEN 0 WHEN status = 'dnf' THEN 1 ELSE 2 END")
                    ->orderBy('id', 'desc')
                    ->get();

                if ($rows->count() > 1) {
                    $keepId = $rows->first()->id;
                    $deleteIds = $rows->where('id', '!=', $keepId)->pluck('id');
                    DB::table('paticipants')->whereIn('id', $deleteIds)->delete();
                }
            }

            // 2. Tambahkan UNIQUE constraint pada kolom no_bib agar tidak pernah bisa duplikat lagi
            $indexes = Schema::getIndexes('paticipants');
            $hasUniqueBib = collect($indexes)->contains(function ($index) {
                return in_array('no_bib', $index['columns']) && $index['unique'];
            });

            if (!$hasUniqueBib) {
                Schema::table('paticipants', function (Blueprint $table) {
                    $table->unique('no_bib', 'paticipants_no_bib_unique');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('paticipants')) {
            $indexes = Schema::getIndexes('paticipants');
            $hasUniqueBib = collect($indexes)->contains(function ($index) {
                return $index['name'] === 'paticipants_no_bib_unique';
            });

            if ($hasUniqueBib) {
                Schema::table('paticipants', function (Blueprint $table) {
                    $table->dropUnique('paticipants_no_bib_unique');
                });
            }
        }
    }
};
