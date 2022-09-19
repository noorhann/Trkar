<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotficationsTable extends Migration
{
    
    public function up()
    {
        Schema::create('notfications', function (Blueprint $table) {
            $table->id();
            $table->integer('notifiable_id');
            $table->string('notifiable_model');
            $table->string('data');
            $table->timestamp('read_at');
            $table->softDeletes();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('notfications');
    }
}
