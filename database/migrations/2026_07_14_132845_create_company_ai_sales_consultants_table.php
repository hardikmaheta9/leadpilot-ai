<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_ai_sales_consultants', function (Blueprint $table) {

            $table->id();

            $table->uuid('company_uuid')->index();

            $table->longText('executive_summary')->nullable();

            $table->longText('business_overview')->nullable();

            $table->longText('digital_maturity')->nullable();

            $table->longText('pain_points')->nullable();

            $table->longText('opportunities')->nullable();

            $table->longText('recommended_services')->nullable();

            $table->longText('recommended_package')->nullable();

            $table->longText('decision_makers')->nullable();

            $table->longText('sales_strategy')->nullable();

            $table->longText('objection_handling')->nullable();

            $table->longText('next_best_action')->nullable();

            $table->integer('opportunity_score')->default(0);

            $table->integer('buying_probability')->default(0);

            $table->bigInteger('estimated_deal_value')->default(0);

            $table->json('service_bundle')->nullable();

            $table->timestamp('generated_at')->nullable();

            $table->timestamps();

            $table->foreign('company_uuid')
                ->references('uuid')
                ->on('companies')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_ai_sales_consultants');
    }
};