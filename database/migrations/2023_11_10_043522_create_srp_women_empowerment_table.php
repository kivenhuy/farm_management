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
        Schema::create('srp_women_empowerment', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->integer('cultivation_id');
            $table->integer('staff_id');
            $table->integer('srps_id');
            $table->unsignedInteger('collection_code')->nullable();
            $table->string('question');
            $table->string('answer');
            $table->integer('score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srp_women_empowerment');
    }
};
