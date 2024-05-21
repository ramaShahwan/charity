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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('state')->nullable();
            $table->string('job')->nullable();
            $table->string('base_pay')->nullable();
            $table->string('overtime_pay')->nullable();
            $table->string('total_pay')->nullable();
            $table->string('birthday')->nullable();
            $table->string('num_family')->nullable();
            $table->string('address')->nullable();

            $table->foreignId('role_id')->nullable();
            $table->foreignId('center_id')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
