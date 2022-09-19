<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditStoreColumns extends Migration
{

    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            
            $table->string('description_ar')->nullable()->change();
            $table->string('description_en')->nullable()->change();

            $table->string('image')->nullable()->change();
            $table->string('banner')->nullable()->change();
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
