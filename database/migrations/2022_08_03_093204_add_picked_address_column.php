<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPickedAddressColumn extends Migration
{
    
    public function up()
    {
        Schema::table('store_branches', function (Blueprint $table) {
            $table->string('branch_picked_address');
        });
    }


    public function down()
    {
        //
    }
}
