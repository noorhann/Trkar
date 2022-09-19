<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBranchTable extends Migration
{
    
    public function up()
    {
        Schema::table('store_branches', function (Blueprint $table) {
            $table->string('address');
            $table->string('phone');
            $table->string('longitude');
            $table->string('latitude');


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
