<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->uuid('company_uuid')->index();

            $table->uuid('contact_uuid')->nullable()->index();

            $table->string('title');

            $table->enum('meeting_type',[
                'in_person',
                'google_meet',
                'zoom',
                'teams',
                'phone'
            ])->default('in_person');

            $table->date('meeting_date');

            $table->time('start_time');

            $table->time('end_time')->nullable();

            $table->string('location')->nullable();

            $table->text('agenda')->nullable();

            $table->text('notes')->nullable();

            $table->text('outcome')->nullable();

            $table->enum('status',[
                'scheduled',
                'completed',
                'cancelled'
            ])->default('scheduled');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->softDeletes();

            $table->index([
                'company_uuid',
                'meeting_date'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};