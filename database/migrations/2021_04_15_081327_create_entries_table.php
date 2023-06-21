<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedInteger('value');
            $table->unsignedInteger('price')->nullable();
            $table->timestamp('date');
            $table->text('description')->nullable();
            $table->unsignedInteger('stock_of_time')->default('0');

            $table->foreignId('product_id')->onDelete('cascade');
            $table->foreignId('store_id')->onDelete('cascade');
            $table->foreignId('worker_id')->onDelete('cascade');
            $table->foreignId('user_id')->onDelete('cascade');
            $table->foreignId('invoice_id')->onDelete('cascade');
            $table->foreignId('unit_id')->onDelete('cascade');

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('worker_id')->references('id')->on('workers');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('unit_id')->references('id')->on('units');

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
        Schema::dropIfExists('entries');
    }
}
