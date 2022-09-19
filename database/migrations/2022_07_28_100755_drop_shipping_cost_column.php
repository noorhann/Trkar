<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropShippingCostColumn extends Migration
{
    
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('shipping_cost');

        });
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('shipping_cost');
            
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
