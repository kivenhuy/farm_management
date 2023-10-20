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
        Schema::create('crop_informations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('crop_category_code');
            $table->string('photo')->nullable();
            $table->integer('duration')->nullable();
            $table->string('duration_type')->nullable()->comment('day(s), week(s), month(s)');
            $table->double('expected_expense')->nullable();
            $table->double('expected_income')->nullable();
            $table->integer('expected_yield')->nullable();
            $table->string('expected_yield_type')->nullable()->comment('Hectare, Acre)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_informations');
    }
};
