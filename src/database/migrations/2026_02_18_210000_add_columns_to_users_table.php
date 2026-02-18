<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('hourly_rate', 8, 2)->nullable()->after('role')->comment('時間単価（円）');
            $table->string('avatar')->nullable()->after('hourly_rate');
            $table->boolean('is_active')->default(true)->after('avatar');
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['hourly_rate', 'avatar', 'is_active']);
        });
    }
};
