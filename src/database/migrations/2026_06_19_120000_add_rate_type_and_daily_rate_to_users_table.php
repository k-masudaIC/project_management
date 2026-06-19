<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rate_type', ['hourly', 'daily'])->default('hourly')->after('role');
            $table->decimal('daily_rate', 10, 2)->nullable()->after('hourly_rate')->comment('日単価（円）');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rate_type', 'daily_rate']);
        });
    }
};
