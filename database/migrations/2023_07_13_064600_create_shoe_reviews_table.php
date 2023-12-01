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
        Schema::create('shoe_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Anonymous User');
            $table->string('shoe_id');
            $table->string('shoe_name');
            $table->string('shoe_brand');
            $table->tinyInteger('rate');
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoe_reviews');
    }
};
