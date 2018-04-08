<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->integer('post_id')->unsigned();
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onDelete('cascade');
            $table->integer('buy_user_id')->unsigned();
            $table->foreign('buy_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->integer('owner_user_id')->unsigned();
            $table->foreign('owner_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->integer('charge_integration');
            $table->timestamps();
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
