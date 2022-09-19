<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsFromCategories extends Migration
{
   
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_subcategory_id_foreign');
            $table->dropColumn('subcategory_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
