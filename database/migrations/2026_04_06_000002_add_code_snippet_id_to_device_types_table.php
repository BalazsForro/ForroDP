<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('device_types', function (Blueprint $table) {
            $table->foreignId('code_snippet_id')->nullable()->constrained('code_snippets')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('device_types', function (Blueprint $table) {
            $table->dropForeign(['code_snippet_id']);
            $table->dropColumn('code_snippet_id');
        });
    }
};