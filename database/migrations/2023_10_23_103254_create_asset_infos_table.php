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
        Schema::create('asset_infos', function (Blueprint $table) {
            $table->id();
            $table->string('housing_ownership');
            $table->string('house_type');
            $table->string('consumer_electronic');
            $table->string('vehicle');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_infos');
    }
};
