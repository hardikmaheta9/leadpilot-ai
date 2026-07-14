<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_ai_recommendations', function (Blueprint $table) {
            $table->id();

            $table->uuid('company_uuid')->index();

            $table->string('type', 100);
            $table->string('title');

            $table->text('description')->nullable();
            $table->text('reason')->nullable();

            $table->string('recommended_service')->nullable();

            $table->unsignedTinyInteger('priority_score')->default(0);
            $table->string('priority', 20)->default('medium');

            $table->unsignedTinyInteger('buying_probability')->default(0);

            $table->unsignedInteger('estimated_value_min')->nullable();
            $table->unsignedInteger('estimated_value_max')->nullable();

            $table->string('status', 30)->default('new');

            $table->json('evidence')->nullable();
            $table->json('suggested_actions')->nullable();

            $table->timestamp('generated_at')->nullable();

            $table->timestamps();

            $table->foreign('company_uuid')
                ->references('uuid')
                ->on('companies')
                ->cascadeOnDelete();

            $table->index([
                'company_uuid',
                'priority',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_ai_recommendations');
    }
};