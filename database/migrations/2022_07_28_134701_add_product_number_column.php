<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductNumberColumn extends Migration
{
    
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->string('product_number')->nullable();

        });
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('order_number')->nullable()->change();
            
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
