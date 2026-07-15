<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_ai_generated_contents', function (Blueprint $table) {

            $table->id();

            $table->uuid('company_uuid')->index();

            $table->string('content_type',60);

            $table->string('title')->nullable();

            $table->text('subject')->nullable();

            $table->longText('content');

            $table->string('generator',40)->default('template');

            $table->string('language',10)->default('en');

            $table->unsignedInteger('version')->default(1);

            $table->unsignedInteger('regenerated_count')->default(0);

            $table->timestamp('generated_at')->nullable();

            $table->timestamps();

            $table->foreign('company_uuid')
                ->references('uuid')
                ->on('companies')
                ->cascadeOnDelete();

            $table->index([
                'company_uuid',
                'content_type'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'company_ai_generated_contents'
        );
    }
};

