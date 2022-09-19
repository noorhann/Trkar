<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStoreRejectStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_reject_statuses', function (Blueprint $table) {
            
            $table->dropColumn('commercial_number');
            $table->dropColumn('commercial_docs');
            $table->dropColumn('tax_card_number');
            $table->dropColumn('tax_card_docs');
            $table->dropColumn('bank_account');
            $table->dropColumn('bank_docs');
            $table->dropColumn('store_name');
            $table->dropColumn('wholesale_docs');  
            $table->integer('store_id')->nullable();
            $table->integer('reject_status_id')->nullable();
            $table->integer('status')->default(1);

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
