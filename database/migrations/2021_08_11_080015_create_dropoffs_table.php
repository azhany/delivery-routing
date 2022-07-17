<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropoffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropoffs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->string('dropoff_point');
            $table->string('dropoff_address');
            $table->string('dropoff_time');
            $table->string('recipient_name');
            $table->string('recipient_phone_no');
            $table->string('recipient_floor_house_no');
            $table->string('recipient_type_of_item')->nullable();
            $table->string('recipient_weight_of_item')->nullable();
            $table->string('recipient_notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dropoffs');
    }
}
