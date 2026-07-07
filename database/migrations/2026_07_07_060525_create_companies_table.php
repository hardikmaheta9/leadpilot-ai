<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('tenant_id')->nullable();

            $table->string('company_name');
            $table->string('legal_name')->nullable();
            $table->string('website')->nullable();
            $table->string('domain')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('phone', 50)->nullable();

            $table->string('industry')->nullable();
            $table->string('company_size')->nullable();

            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();

            $table->string('linkedin_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();

            $table->string('source')->nullable();
            $table->enum('status', [
                'prospect',
                'qualified',
                'customer',
                'inactive',
                'blacklisted',
            ])->default('prospect')->index();

            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};