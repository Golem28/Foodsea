<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('user_favourite_recipe', function (Blueprint $table) {
            $table->uuid('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->uuid('recipe_id')->references('id')->on('recipe')->constrained()->onDelete('cascade');

            $keys = array('user_id', 'recipe_id');
            $table->primary($keys);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('user_favourite_recipe');
    }
};
