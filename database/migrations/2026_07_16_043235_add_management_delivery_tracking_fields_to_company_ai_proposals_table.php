<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_ai_proposals', function (Blueprint $table) {
            $table->boolean('is_latest')
                ->default(false)
                ->after('version')
                ->index();

            $table->string('proposal_status', 30)
                ->default('draft')
                ->after('is_latest')
                ->index();

            $table->string('public_token', 80)
                ->nullable()
                ->unique()
                ->after('proposal_status');

            $table->timestamp('public_token_expires_at')
                ->nullable()
                ->after('public_token');

            $table->string('pdf_path')
                ->nullable()
                ->after('public_token_expires_at');

            $table->string('docx_path')
                ->nullable()
                ->after('pdf_path');

            $table->string('email_sent_to')
                ->nullable()
                ->after('docx_path');

            $table->unsignedBigInteger('email_sent_by')
                ->nullable()
                ->after('email_sent_to');

            $table->timestamp('sent_at')
                ->nullable()
                ->after('email_sent_by');

            $table->timestamp('delivered_at')
                ->nullable()
                ->after('sent_at');

            $table->timestamp('viewed_at')
                ->nullable()
                ->after('delivered_at');

            $table->timestamp('last_viewed_at')
                ->nullable()
                ->after('viewed_at');

            $table->unsignedInteger('view_count')
                ->default(0)
                ->after('last_viewed_at');

            $table->timestamp('downloaded_at')
                ->nullable()
                ->after('view_count');

            $table->unsignedInteger('download_count')
                ->default(0)
                ->after('downloaded_at');

            $table->timestamp('accepted_at')
                ->nullable()
                ->after('download_count');

            $table->timestamp('rejected_at')
                ->nullable()
                ->after('accepted_at');

            $table->timestamp('change_requested_at')
                ->nullable()
                ->after('rejected_at');

            $table->text('client_response_note')
                ->nullable()
                ->after('change_requested_at');

            $table->timestamp('archived_at')
                ->nullable()
                ->after('client_response_note');

            $table->timestamp('expires_at')
                ->nullable()
                ->after('archived_at');
        });
    }

    public function down(): void
    {
        Schema::table('company_ai_proposals', function (Blueprint $table) {
            $table->dropUnique([
                'public_token',
            ]);

            $table->dropIndex([
                'is_latest',
            ]);

            $table->dropIndex([
                'proposal_status',
            ]);

            $table->dropColumn([
                'is_latest',
                'proposal_status',
                'public_token',
                'public_token_expires_at',
                'pdf_path',
                'docx_path',
                'email_sent_to',
                'email_sent_by',
                'sent_at',
                'delivered_at',
                'viewed_at',
                'last_viewed_at',
                'view_count',
                'downloaded_at',
                'download_count',
                'accepted_at',
                'rejected_at',
                'change_requested_at',
                'client_response_note',
                'archived_at',
                'expires_at',
            ]);
        });
    }
};