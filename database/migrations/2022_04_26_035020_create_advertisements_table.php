<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{

    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adv_postion_id');
            $table->foreign('adv_postion_id')->references('id')->on('adv_postions')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('status');
            $table->string('name');
            $table->string('url');
            $table->string('image');
            $table->string('platform');
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
        Schema::dropIfExists('advertisements');
    }
}
