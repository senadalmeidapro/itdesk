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
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('service_slug')->nullable()->after('audience');
            $table->string('status')->default('new')->index()->after('phone');
            $table->foreignId('converted_ticket_id')->nullable()->after('message')
                ->constrained('tickets')->nullOnDelete();
            $table->timestamp('contacted_at')->nullable()->after('converted_ticket_id');
            $table->timestamp('converted_at')->nullable()->after('contacted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('converted_ticket_id');
            $table->dropColumn(['service_slug', 'status', 'contacted_at', 'converted_at']);
        });
    }
};
