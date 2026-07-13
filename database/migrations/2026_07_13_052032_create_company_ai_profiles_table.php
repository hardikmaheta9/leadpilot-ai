<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_ai_profiles', function (Blueprint $table) {
            $table->id();

            $table->uuid('company_uuid')->unique();

            $table->longText('company_summary')->nullable();
            $table->longText('business_description')->nullable();

            $table->string('industry')->nullable();
            $table->string('business_type')->nullable();

            $table->unsignedInteger('employee_estimate')->nullable();
            $table->unsignedSmallInteger('founded_year')->nullable();
            $table->string('headquarters')->nullable();

            $table->unsignedTinyInteger('lead_score')->default(0);
            $table->string('lead_grade', 10)->nullable();

            $table->unsignedTinyInteger('confidence_score')->default(0);

            $table->timestamp('last_analyzed_at')->nullable();

            $table->timestamps();

            $table->foreign('company_uuid')
                ->references('uuid')
                ->on('companies')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_ai_profiles');
    }
};