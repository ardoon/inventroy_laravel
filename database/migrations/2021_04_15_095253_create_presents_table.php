<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presents', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('entrance');
            $table->string('leaving');
            
            $table->foreignId('user_id')->onDelete('cascade');
            $table->foreignId('worker_id')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('worker_id')->references('id')->on('workers');
            
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
        Schema::dropIfExists('presents');
    }
}
