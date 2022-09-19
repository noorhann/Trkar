<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtpColumn extends Migration
{
    
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            
            $table->integer('otp')->nullable();
        });
    }

    
    public function down()
    {
        //
    }
}
