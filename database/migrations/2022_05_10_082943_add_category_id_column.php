<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdColumn extends Migration
{
    
    public function up()
    {
        Schema::table('car_mades', function (Blueprint $table) {
            
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
        
        });
    }

    
    public function down()
    {
        //
    }
}
