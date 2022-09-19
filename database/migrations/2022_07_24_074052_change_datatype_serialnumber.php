<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypeSerialnumber extends Migration
{
    
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('serial_number')->change();
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
