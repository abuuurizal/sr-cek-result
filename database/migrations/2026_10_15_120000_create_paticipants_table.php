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
        Schema::create('paticipants', function (Blueprint $table) {
            $table->id();
            $table->string('chip_code')->nullable();
            $table->string('no_bib')->nullable();
            $table->string('nama_lengkap');
            $table->string('gender')->nullable();
            $table->string('kategori')->nullable();
            $table->string('sub_kategori')->nullable();
            $table->enum('status', ['finished', 'dns', 'dnf'])->default('dns');
            $table->string('pace')->nullable();
            $table->string('gun_time')->nullable();
            $table->string('chip_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paticipants');
    }
};
