<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumns extends Migration
{
    
    public function up()
    {
        Schema::table('car_mades', function (Blueprint $table) {
            
            $table->string('image')->nullable();
        });

        Schema::table('manufacturers', function (Blueprint $table) {
            
            $table->string('image')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();

        });
        Schema::table('car_models', function (Blueprint $table) {
            
            $table->unsignedBigInteger('car_made_id')->nullable();
            $table->foreign('car_made_id')->references('id')->on('car_mades')->onUpdate('cascade')->onDelete('cascade');
        
        });
        Schema::table('car_mades', function (Blueprint $table) {
            
            $table->dropForeign('car_mades_car_made_id_foreign');

            $table->dropColumn('car_made_id');
            
        });
    }

    
    public function down()
    {
        //
    }
}
