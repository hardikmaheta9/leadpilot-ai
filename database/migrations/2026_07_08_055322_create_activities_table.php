<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('module');

            $table->uuid('module_uuid');

            $table->unsignedBigInteger('tenant_id')->nullable()->index();

            $table->string('action');

            $table->text('description')->nullable();

            $table->foreignId('performed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['module', 'module_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};