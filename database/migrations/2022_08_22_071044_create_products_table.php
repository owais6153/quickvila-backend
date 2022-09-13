<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('image')->nullable()->default('images/no-image.png');
            $table->longText('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('price');
            $table->string('sale_price')->nullable();
            $table->string('gallery')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->boolean('manage_able')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('products');
    }
}
