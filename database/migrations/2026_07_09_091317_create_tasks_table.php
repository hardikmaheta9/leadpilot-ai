<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->uuid('company_uuid')->nullable()->index();
            $table->uuid('contact_uuid')->nullable()->index();

            $table->string('title');
            $table->text('description')->nullable();

            $table->date('due_date')->nullable();
            $table->string('priority')->default('medium')->index(); // low, medium, high
            $table->string('status')->default('pending')->index(); // pending, in_progress, completed, cancelled

            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_uuid', 'status']);
            $table->index(['due_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};