<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_tag')->unique();
            $table->string('serial_number')->nullable()->unique();
            $table->string('name');
            $table->enum('type', [
                'laptop', 'desktop', 'cpu', 'monitor', 'hard_disk',
                'keyboard', 'mouse', 'printer', 'switch', 'router',
                'camera', 'other',
            ]);
            $table->enum('status', ['in_use', 'in_stock', 'retired', 'repair'])->default('in_stock');

            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->json('specifications')->nullable();

            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();

            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

            $table->string('supplier')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expires_at')->nullable();
            $table->string('location')->nullable();

            $table->string('qr_code')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};