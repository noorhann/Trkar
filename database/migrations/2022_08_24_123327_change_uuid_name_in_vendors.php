<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUuidNameInVendors extends Migration
{
    
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->renameColumn('uuid', 'vendor_uuids_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
        });
    }
}
