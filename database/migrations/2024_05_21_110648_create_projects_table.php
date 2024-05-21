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
            $table->string('name')->nullabble();
            $table->string('description')->nullabble();
            $table->string('image')->nullabble();
            $table->string('tag')->nullabble();
            $table->string('visits_count')->nullabble();
            $table->string('benefits_count')->nullabble();
            $table->string('target')->nullabble();
            $table->string('total_budget')->nullabble();
            $table->string('total_donate')->nullabble();
            $table->boolean('finish')->default(0)->nullabble();
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
