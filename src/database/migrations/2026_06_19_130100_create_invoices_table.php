<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('billing_month');
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->enum('rate_type', ['hourly', 'daily'])->default('hourly');
            $table->decimal('unit_rate', 10, 2)->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('status', ['draft', 'issued', 'paid'])->default('issued');
            $table->date('issued_at')->nullable();
            $table->date('due_date')->nullable();
            $table->boolean('auto_generated')->default(true);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'billing_month']);
            $table->index(['billing_month', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
