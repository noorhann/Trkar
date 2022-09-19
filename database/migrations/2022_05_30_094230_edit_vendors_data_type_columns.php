<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditVendorsDataTypeColumns extends Migration
{
    
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            
            $table->string('bank_account')->nullable()->change();
            $table->string('commercial_number')->nullable()->change();
            $table->string('tax_card_number')->nullable()->change();
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
