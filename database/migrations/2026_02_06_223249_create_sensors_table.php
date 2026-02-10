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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('device_id')
                ->constrained('devices')
                ->cascadeOnDelete();

            $table->string('name', 45);
            $table->string('key', 45);
            $table->string('description', 255)->nullable();
            $table->unsignedTinyInteger('display_sort_order')->nullable();
            $table->boolean('is_required')->default(false);

            $table->integer('min_value')->nullable();
            $table->integer('max_value')->nullable();

            $table->string('unit_type')->nullable();
            $table->unsignedTinyInteger('data_type');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
