<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorUuidsTable extends Migration
{
    
    public function up()
    {
        Schema::create('vendor_uuids', function (Blueprint $table) {
            $table->id();
            $table->string('retail')->nullable();
            $table->string('wholesale')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('vendor_uuids');
    }
}
