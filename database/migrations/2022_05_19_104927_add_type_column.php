<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeColumn extends Migration
{
    
    public function up()
    {
        Schema::table('attribute_tyres', function (Blueprint $table) {
            
            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('tyre_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        //
    }
}
