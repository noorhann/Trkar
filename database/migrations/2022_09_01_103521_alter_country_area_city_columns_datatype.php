<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCountryAreaCityColumnsDatatype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
              $table->string('country_id')->change();
              $table->string('city_id')->change();
              $table->string('area_id')->change();            
        });

        Schema::table('shippings', function (Blueprint $table) {
            $table->string('country_id')->change();
            $table->string('city_id')->change();
            $table->string('area_id')->change();

        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('country_id')->change();
            $table->string('city_id')->change();
            $table->string('area_id')->change();

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
