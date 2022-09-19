<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DropSubCategoriesColumn extends Migration
{
    
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('sub_categories_attributes');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }


    public function down()
    {
        //
    }
}
