<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('call_logs', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->uuid('company_uuid')->index();

            $table->uuid('contact_uuid')->nullable()->index();

            $table->enum('call_type',[
                'incoming',
                'outgoing'
            ]);

            $table->string('subject');

            $table->date('call_date');

            $table->time('call_time');

            $table->integer('duration')->default(0); // minutes

            $table->text('notes')->nullable();

            $table->text('outcome')->nullable();

            $table->enum('status',[
                'completed',
                'missed',
                'scheduled'
            ])->default('completed');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->softDeletes();

            $table->index([
                'company_uuid',
                'call_date'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};