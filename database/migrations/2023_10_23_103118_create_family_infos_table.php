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
        Schema::create('family_infos', function (Blueprint $table) {
            $table->id();
            $table->string('education');
            $table->string('marial_status');
            $table->string('parent_name');
            $table->string('spouse_name');
            $table->string('no_of_family');
            $table->string('education');
            $table->longText('total_child_under_18');
            $table->longText('total_child_under_18_going_school');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_infos');
    }
};
