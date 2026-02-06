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
        Schema::create('device_latest_states', function (Blueprint $table) {
            $table->foreignId('device_id')
                ->constrained('devices')
                ->cascadeOnDelete()
                ->primary();

            $table->json('value')->comment('JSON encoded');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_latest_states');
    }
};
