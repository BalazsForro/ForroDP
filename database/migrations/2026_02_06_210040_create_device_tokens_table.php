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
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('device_id')
                ->constrained('devices')
                ->cascadeOnDelete()
                ->unique();
            $table->string('prefix', 9);
            $table->string('token_hash', 64)->unique();
            $table->unsignedTinyInteger('rate_limit')->default(60);
            $table->timestamp('last_used_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
    }
};
