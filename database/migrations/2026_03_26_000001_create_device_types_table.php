<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('device_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('icon', 50)->nullable();
            $table->timestamps();
        });

        DB::table('device_types')->insert([
            ['id' => 1, 'name' => 'Arduino', 'icon' => 'bi-cpu', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'ESP32', 'icon' => 'bi-broadcast', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Raspberry Pi', 'icon' => 'bi-server', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Other', 'icon' => 'bi-gear', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::table('devices', function (Blueprint $table) {
            $table->unsignedBigInteger('device_type_id')->nullable()->after('type');
        });

        DB::statement('UPDATE devices SET device_type_id = type');

        Schema::table('devices', function (Blueprint $table) {
            $table->unsignedBigInteger('device_type_id')->nullable(false)->change();
            $table->foreign('device_type_id')->references('id')->on('device_types');
            $table->dropColumn('type');
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropForeign(['device_type_id']);
            $table->integer('type')->nullable()->after('device_type_id');
        });

        DB::statement('UPDATE devices SET type = device_type_id');

        Schema::table('devices', function (Blueprint $table) {
            $table->integer('type')->nullable(false)->change();
            $table->dropColumn('device_type_id');
        });

        Schema::dropIfExists('device_types');
    }
};
