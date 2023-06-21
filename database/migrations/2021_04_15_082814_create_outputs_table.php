<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outputs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('value');
            $table->dateTime('date');
            $table->text('description')->nullable();
            $table->unsignedInteger('stock_of_time')->default('0');

            $table->foreignId('product_id')->onDelete('cascade');
            $table->foreignId('unit_id')->onDelete('cascade');
            $table->foreignId('user_id')->onDelete('cascade');
            $table->foreignId('worker_id')->onDelete('cascade');
            $table->foreignId('part_id')->onDelete('cascade');
            $table->foreignId('store_id')->onDelete('cascade');
            $table->foreignId('receipt_id')->onDelete('cascade');

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('worker_id')->references('id')->on('workers');
            $table->foreign('part_id')->references('id')->on('parts');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('receipt_id')->references('id')->on('receipts');

            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outputs');
    }
}
