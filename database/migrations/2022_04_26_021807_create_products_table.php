<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('product_type_id');
            $table->integer('serial_number');
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('details_en');
            $table->string('details_ar');
            $table->double('price');
            $table->double('actual_price');
            $table->double('discount');
            $table->string('image');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('subcategory_id');
            $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('car_made_id');
            $table->foreign('car_made_id')->references('id')->on('car_mades')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('year_id');
            $table->foreign('year_id')->references('id')->on('years')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('manufacturer_id');
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('original_country_id');
            $table->foreign('original_country_id')->references('id')->on('original_countries')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('approved');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
