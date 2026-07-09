<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->uuid('company_uuid')->index();

            $table->uuid('contact_uuid')->nullable()->index();

            $table->string('title');

            $table->string('category')->default('General')->index();

            $table->string('original_filename');

            $table->string('stored_filename');

            $table->string('mime_type')->nullable();

            $table->unsignedBigInteger('file_size')->default(0);

            $table->string('disk')->default('public');

            $table->string('path');

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->softDeletes();

            $table->index(['company_uuid','category']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};