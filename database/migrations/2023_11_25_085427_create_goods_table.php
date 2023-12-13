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
        Schema::create('goods', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->boolean('availability')->default(false);
            $table->text('description');
            $table->string('owner_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('price_id');
            $table->string('title');
            $table->integer('date')->nullable();
            $table->boolean('is_owner');
            $table->boolean('is_adult');
            $table->text('thumb_photo');
            $table->unsignedBigInteger('item_rating');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};
