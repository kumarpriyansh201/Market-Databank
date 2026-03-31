<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('file_path');
            $table->string('file_type')->nullable()->comment('MIME type of the file')->after('original_filename');
            $table->unsignedBigInteger('file_size')->nullable()->comment('File size in bytes')->after('file_type');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('file_size')->comment('For file upload approval workflow');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejection_reason')->nullable()->after('approved_at');

            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'original_filename',
                'file_type',
                'file_size',
                'status',
                'approved_by',
                'approved_at',
                'rejection_reason',
            ]);
        });
    }
};
