<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attributes', function (Blueprint $table) {
            
            $table->string('name');
            $table->dropForeign('attributes_category_id_foreign');
            $table->dropColumn('category_id');
            $table->dropColumn('name_ar');
            $table->dropColumn('name_en');
            $table->dropColumn('details_ar');
            $table->dropColumn('details_en');
            $table->dropColumn('status');

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
