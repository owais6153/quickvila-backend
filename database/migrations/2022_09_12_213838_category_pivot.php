<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategoryPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->unsigned()->nullable()->index();
            $table->bigInteger('pivot_id')->unsigned()->nullable()->index();
            $table->enum('type', ['store', 'product'])->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pivot_categories');
    }
}
