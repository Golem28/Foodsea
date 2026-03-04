<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('recipe_categories_recipe', function (Blueprint $table) {
            $table->string('recipe_id')->references('id')->on('recipes')->constrained()->onDelete('cascade');
            $table->string('recipe_category_id')->references('id')->on('recipe_categories')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('recipe_categories_recipe');
    }
};
