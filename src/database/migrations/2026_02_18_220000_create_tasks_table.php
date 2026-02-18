<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['not_started', 'in_progress', 'in_review', 'completed', 'on_hold'])->default('not_started');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->decimal('estimated_hours', 8, 2)->nullable()->comment('見積工数（時間）');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->integer('sort_order')->default(0)->comment('表示順');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['project_id', 'status']);
            $table->index(['due_date']);
            $table->index(['status', 'priority']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
