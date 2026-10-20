<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('sync_logs')) {
            Schema::create('sync_logs', function (Blueprint $table) {
                $table->id();
                $table->string('batch_id')->nullable()->index();
                $table->string('event_name')->nullable();
                $table->unsignedInteger('records_count')->default(0);
                $table->string('ip_address', 45)->nullable();
                $table->string('status', 20)->default('success');
                $table->text('message')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};
