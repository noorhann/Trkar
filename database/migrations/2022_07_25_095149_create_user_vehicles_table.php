<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('made_id');
            $table->foreign('made_id')->references('id')->on('car_mades')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('model_id');
            $table->foreign('model_id')->references('id')->on('car_models')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('engine_id');
            $table->foreign('engine_id')->references('id')->on('car_engines')->onUpdate('cascade')->onDelete('cascade');
            
            $table->softDeletes();
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
        Schema::dropIfExists('user_vehicles');
    }
}
