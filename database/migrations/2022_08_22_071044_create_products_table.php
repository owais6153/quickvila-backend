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
            $table->longText('product_id')->nullable();
            $table->string('name');
            $table->longText('image')->nullable();
            $table->longText('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('price');
            $table->string('sale_price')->nullable();
            $table->string('price_to_display')->nullable();
            $table->string('sale_price_to_display')->nullable();
            $table->enum('product_type', ['simple', 'variation'])->default('simple');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->boolean('manage_able')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('gallery')->nullable();
            $table->boolean('is_site_featured')->default(false);
            $table->boolean('is_store_featured')->default(false);
            $table->enum('status', [Published(), Draft()])->default(Draft());
            $table->boolean('is_tax_free')->default(false);
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
