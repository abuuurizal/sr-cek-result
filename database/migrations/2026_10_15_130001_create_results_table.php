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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('Bib')->nullable()->index();
            $table->string('Location')->nullable();
            $table->string('GunTime')->nullable();
            $table->string('ChipTime')->nullable();
            $table->string('Time')->nullable();
            $table->string('Rank')->nullable();
            $table->string('OverallRank')->nullable();
            $table->string('GenderRank')->nullable();
            $table->string('CategoryRank')->nullable();
            $table->string('Status')->nullable();
            $table->timestamps();

            // Composite index for fast kiosk query lookups
            $table->index(['Bib', 'Location']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
