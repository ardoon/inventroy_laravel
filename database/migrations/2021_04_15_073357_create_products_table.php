<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();

            $table->foreignId('unit_id')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units');

            $table->foreignId('unit_sub_id')->nullable()->onDelete('cascade');
            $table->foreign('unit_sub_id')->references('id')->on('units');

            $table->double('proportion', 10, 6)->nullable();

            $table->unsignedInteger('stock')->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
