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
            $table->string('share_token', 64)->nullable()->unique()->after('pdf_path');
            $table->timestamp('whatsapp_opened_at')->nullable()->after('generated_at');
            $table->foreignId('whatsapp_opened_by')->nullable()->after('whatsapp_opened_at')->constrained('users')->nullOnDelete();
            $table->unsignedInteger('whatsapp_open_count')->default(0)->after('whatsapp_opened_by');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('sample_status', ['pending_collection', 'collected', 'received_in_lab'])
                ->default('pending_collection')
                ->after('status')
                ->index();
            $table->timestamp('sample_collected_at')->nullable()->after('sample_status');
            $table->timestamp('sample_received_at')->nullable()->after('sample_collected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['whatsapp_opened_by']);
            $table->dropColumn([
                'share_token',
                'whatsapp_opened_at',
                'whatsapp_opened_by',
                'whatsapp_open_count',
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'sample_status',
                'sample_collected_at',
                'sample_received_at',
            ]);
        });
    }
};
