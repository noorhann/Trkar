<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubcatColumn extends Migration
{
    
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            
            $table->string('subcategories')->nullable();

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
