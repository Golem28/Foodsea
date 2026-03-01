<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('recipe_ingredient', function (Blueprint $table) {
            $table->uuid('ingredient_id')->references('id')->on('ingredients')->constrained()->onDelete('cascade');
            $table->uuid('recipe_id')->references('id')->on('recipes')->constrained()->onDelete('cascade');

            $table->primary(['ingredient_id', 'recipe_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('recipe_ingredient');
    }
};
