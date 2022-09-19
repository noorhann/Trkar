<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCarMadeIdColumn extends Migration
{
    
    public function up()
    {
        Schema::table('car_mades', function (Blueprint $table) {
            
            $table->unsignedBigInteger('car_made_id')->nullable();
            $table->foreign('car_made_id')->references('id')->on('car_mades')->onUpdate('cascade')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
