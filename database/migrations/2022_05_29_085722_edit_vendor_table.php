<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditVendorTable extends Migration
{
    
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            
            $table->string('uuid')->nullable()->change();
            $table->integer('bank_account')->nullable()->change();
            $table->integer('commercial_number')->nullable()->change();
            $table->integer('tax_card_number')->nullable()->change();
            $table->integer('approved')->nullable()->change();

            $table->string('notes')->nullable()->change();
            $table->unsignedBigInteger('country_id')->nullable()->change();
            $table->unsignedBigInteger('city_id')->nullable()->change();
            $table->unsignedBigInteger('area_id')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('longitude')->nullable()->change();
            $table->string('latitude')->nullable()->change();
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
