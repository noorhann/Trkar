<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DropSubCategoriesTable extends Migration
{

    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('sub_categories');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down()
    {
        //
    }
}
