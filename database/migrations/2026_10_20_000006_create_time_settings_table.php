<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_settings', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->timestamp('gun_time')->nullable();
            $table->time('fastest');
            $table->timestamps();
        });

        // Seed data awal jika tabel baru dibuat
        DB::table('time_settings')->insert([
            [
                'kategori' => '5K Fun Run',
                'gun_time' => '2026-10-20 11:46:26',
                'fastest' => '00:14:30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => '10K Race',
                'gun_time' => '2026-10-20 05:45:00',
                'fastest' => '00:29:15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => '21K Half Marathon',
                'gun_time' => '2026-10-20 05:15:00',
                'fastest' => '01:03:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => '42K Full Marathon',
                'gun_time' => '2026-10-20 04:45:00',
                'fastest' => '02:08:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_settings');
    }
};
