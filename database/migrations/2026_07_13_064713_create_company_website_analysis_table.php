<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_website_analysis', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relationship
            |--------------------------------------------------------------------------
            */

            $table->uuid('company_uuid')->unique();

            /*
            |--------------------------------------------------------------------------
            | Website
            |--------------------------------------------------------------------------
            */

            $table->string('website_url')->nullable();

            $table->string('website_title')->nullable();

            $table->text('meta_description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Technology
            |--------------------------------------------------------------------------
            */

            $table->string('cms')->nullable();

            $table->string('framework')->nullable();

            $table->string('programming_language')->nullable();

            $table->string('server')->nullable();

            $table->json('technologies')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Website Metrics
            |--------------------------------------------------------------------------
            */

            $table->boolean('ssl_enabled')->default(false);

            $table->boolean('mobile_friendly')->default(false);

            $table->boolean('has_blog')->default(false);

            $table->boolean('has_contact_page')->default(false);

            $table->boolean('has_about_page')->default(false);

            $table->boolean('has_services_page')->default(false);

            /*
            |--------------------------------------------------------------------------
            | Counts
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('images')->default(0);

            $table->unsignedInteger('forms')->default(0);

            $table->unsignedInteger('scripts')->default(0);

            $table->unsignedInteger('stylesheets')->default(0);

            $table->unsignedInteger('word_count')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Scores
            |--------------------------------------------------------------------------
            */

            $table->unsignedTinyInteger('seo_score')->default(0);

            $table->unsignedTinyInteger('performance_score')->default(0);

            $table->unsignedTinyInteger('website_score')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Analysis
            |--------------------------------------------------------------------------
            */

            $table->timestamp('analyzed_at')->nullable();

            $table->timestamps();

            $table->foreign('company_uuid')
                ->references('uuid')
                ->on('companies')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_website_analysis');
    }
};