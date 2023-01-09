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
            $table->string('order_no')->unique();
            $table->string('sub_total')->nullable();
            $table->string('platform_charges')->nullable();
            $table->string('delivery_charges')->nullable();
            $table->string('tax')->nullable();
            $table->string('total');
            $table->string('tip')->nullable();
            $table->string('note')->nullable();
            $table->longText('prescription')->nullable();
            $table->enum('status', [Completed(), Canceled(), InProcess(), Refunded(), PendingPayment()])->defaul(PendingPayment());
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('check_for_refunds')->default(false);
            $table->longText('payment_id')->nullable();
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
