<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInvoiceTable extends Migration
{
 
    
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('Vat_no')->nullable();
            $table->dropForeign(['vendor_id']);
            $table->dropColumn('vendor_id');
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
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
