<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('paticipants')) {
            Schema::create('paticipants', function (Blueprint $table) {
                $table->id();
                $table->string('chip_code')->nullable()->index();
                $table->string('no_bib')->unique();
                $table->string('nama_lengkap');
                $table->string('gender')->nullable();
                $table->string('kategori')->nullable()->index();
                $table->string('sub_kategori')->nullable();
                $table->enum('status', ['finished', 'dns', 'dnf'])->default('dns')->index();
                $table->string('pace')->nullable();
                $table->string('gun_time')->nullable();
                $table->string('chip_time')->nullable();
                $table->timestamp('synced_at')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('paticipants', function (Blueprint $table) {
                if (!Schema::hasColumn('paticipants', 'synced_at')) {
                    $table->timestamp('synced_at')->nullable()->after('chip_time');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paticipants');
    }
};
