<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_unit', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ratio');
            $table->foreignId('parent_id')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('units');
            $table->foreignId('unit_id')->onDelete('cascade');
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
        Schema::dropIfExists('unit_unit');
    }
}
