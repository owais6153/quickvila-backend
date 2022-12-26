<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('count')->default(0);
            $table->string('sub_total')->nullable();
            $table->string('platform_charges')->nullable();
            $table->string('delivery_charges')->nullable();
            $table->string('tax')->nullable();
            $table->string('total');
            $table->string('address1');
            $table->string('address2');
            $table->string('latitude');
            $table->string('longitude');
            $table->enum('status', [Completed(), Canceled(), InProcess(), Refunded()])->defaul(InProcess());
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('check_for_refunds')->default(false);
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
        Schema::dropIfExists('orders');
    }
}
