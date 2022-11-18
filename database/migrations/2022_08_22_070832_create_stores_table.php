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
            $table->longText('logo')->nullable();
            $table->longText('cover')->nullable();
            $table->longText('description')->nullable();
            $table->longText('url')->nullable();
            $table->longText('address')->nullable();
            $table->string('latitude');
            $table->string('longitude');
            $table->boolean('manage_able')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['published', 'draft'])->default('draft');
            $table->enum('type', ['pharmacy', 'adult', 'default'])->default('default');
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
