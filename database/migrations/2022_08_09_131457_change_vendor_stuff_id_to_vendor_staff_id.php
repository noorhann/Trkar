<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeVendorStuffIdToVendorStaffId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_vendor_staff', function (Blueprint $table) {
            $table->renameColumn('vendor_stuff_id','vendor_staff_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_staff_id', function (Blueprint $table) {
            //
        });
    }
}
