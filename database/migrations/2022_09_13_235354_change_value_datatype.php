<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeValueDatatype extends Migration
{
    
    public function up()
    {
        Schema::table('product_attributes', function (Blueprint $table) 
        {
            $table->json('value')->change();
            $table->string('key')->nullable()->change();

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
