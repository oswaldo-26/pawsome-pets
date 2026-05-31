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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('species', ['dog', 'cat', 'small_pet']);
            $table->string('breed')->nullable();
            $table->enum('age_group', ['baby', 'young', 'adult', 'senior']);
            $table->integer('age_months')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->enum('size', ['small', 'medium', 'large'])->nullable();
            $table->text('description')->nullable();
            $table->string('photo')->nullable(); //url or path para sa pic ng pet
            $table->boolean('is_vaccinated')->default(false);
            $table->boolean('is_neutered')->default(false);
            $table->boolean('is_housetrained')->default(false);
            $table->boolean('good_with_kids')->default(false);
            $table->boolean('good_with_pets')->default(false);
            $table->enum('status', ['available', 'pending', 'adopted'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
