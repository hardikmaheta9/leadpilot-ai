<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_ai_proposals', function (Blueprint $table) {

            $table->id();

            $table->uuid('company_uuid')->index();

            $table->string('proposal_title');

            $table->longText('proposal_html');

            $table->longText('executive_summary')->nullable();

            $table->longText('scope')->nullable();

            $table->longText('timeline')->nullable();

            $table->longText('investment')->nullable();

            $table->longText('roi')->nullable();

            $table->unsignedInteger('version')->default(1);

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
        Schema::dropIfExists(
            'company_ai_proposals'
        );
    }
};