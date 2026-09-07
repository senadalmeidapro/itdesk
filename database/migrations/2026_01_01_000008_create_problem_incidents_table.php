<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('problem_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('problem_ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('incident_ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['problem_ticket_id', 'incident_ticket_id'], 'problem_incident_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('problem_incidents');
    }
};
