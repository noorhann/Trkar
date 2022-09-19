<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropVendorIdColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->integer('vendor_id')->nullable();
            $table->integer('store_id')->nullable();


        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_vendor_id_foreign');
            $table->dropForeign('orders_store_id_foreign');
            $table->dropColumn('vendor_id');
            $table->dropColumn('store_id');
            $table->integer('grand_total')->nullable()->change();
            $table->integer('discount')->nullable()->change();


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
