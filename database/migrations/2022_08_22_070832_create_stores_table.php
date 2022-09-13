<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('logo')->default('images/no-image.png')->nullable();
            $table->longText('cover')->default('images/no-image.png')->nullable();
            $table->longText('description')->nullable();
            $table->longText('url')->nullable();
            $table->longText('address')->nullable();
            $table->integer('latitude');
            $table->integer('longitude');
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
        Schema::dropIfExists('stores');
    }
}
