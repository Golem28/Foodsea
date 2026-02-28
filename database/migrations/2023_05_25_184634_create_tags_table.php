<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('recipe_tags', function (Blueprint $table) {
            $table->id();
            $table->string('recipe_id')->references('id')->on('recipes')->constrained()->onDelete('cascade');
            $table->string('tag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('recipe_tag');
    }
};
