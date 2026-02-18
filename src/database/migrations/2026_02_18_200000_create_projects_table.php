<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients');
            $table->string('name');
            $table->string('code', 50)->nullable()->unique()->comment('案件コード（例：PRJ-2024-001）');
            $table->text('description')->nullable();
            $table->enum('status', ['proposal', 'in_progress', 'on_hold', 'completed', 'cancelled'])->default('proposal');
            $table->decimal('budget', 12, 2)->nullable()->comment('予算（円）');
            $table->decimal('estimated_hours', 8, 2)->nullable()->comment('見積工数（時間）');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes()->comment('ソフトデリート用');
            $table->index(['status', 'end_date']);
            $table->index(['client_id', 'status']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
