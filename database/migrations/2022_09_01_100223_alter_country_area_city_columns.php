<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCountryAreaCityColumns extends Migration
{
    
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['area_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['country_id']);

            
        });

        Schema::table('shippings', function (Blueprint $table) {

            $table->dropForeign(['country_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['area_id']);

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
