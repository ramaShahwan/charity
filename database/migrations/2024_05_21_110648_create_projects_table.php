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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('tag')->nullable();
            $table->string('total_benifit')->nullable();
            $table->string('benefits_count')->nullable();
            $table->string('target')->nullable();
            $table->string('total_budget')->nullable();
            $table->string('total_donate')->nullable();
            $table->boolean('finish')->default(0)->nullable();
            $table->timestamps();
            
            //classes_table
            $table->foreignId('class_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project');
    }
};
