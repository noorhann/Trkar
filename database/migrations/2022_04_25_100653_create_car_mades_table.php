<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarMadesTable extends Migration
{

    public function up()
    {
        Schema::create('car_mades', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_mades');
    }
}
