<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreRejectStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_reject_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('commercial_number')->nullable();
            $table->string('commercial_docs')->nullable();
            $table->string('tax_card_number')->nullable();
            $table->string('tax_card_docs')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_docs')->nullable();
            $table->string('store_name')->nullable();
            $table->string('wholesale_docs')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_reject_status');
    }
}
