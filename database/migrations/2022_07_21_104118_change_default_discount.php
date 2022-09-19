<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDefaultDiscount extends Migration
{
    
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->double('discount')->default(0);
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
