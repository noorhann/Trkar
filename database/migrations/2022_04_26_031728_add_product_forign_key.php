<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductForignKey extends Migration
{
    
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('product_type_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        //
    }
}
