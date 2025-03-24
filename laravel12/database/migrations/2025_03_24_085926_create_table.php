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
        Schema::create('service', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->timestamps();
        });

        Schema::create('customer_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('service')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('prefix', 10);
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_service_id')->constrained('customer_service')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('urutan');
            $table->boolean('terlayani')->default(false);
            $table->boolean('skip')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
        Schema::dropIfExists('customer_service');
        Schema::dropIfExists('service');
    }
};
