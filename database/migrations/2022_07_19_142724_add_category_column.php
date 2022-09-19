<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryColumn extends Migration
{
    
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('car_model_id')->nullable();
            $table->string('car_engine_id')->nullable();
            $table->string('subcategory_id')->nullable();
            $table->integer('status')->default('0');

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
