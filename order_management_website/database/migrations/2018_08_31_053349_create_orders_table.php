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
            $table->integer('status_id', false, true)->nullable();
            $table->string('name', 30)->nullable();
            $table->string('name_backup', 30)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('phone_backup', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->integer('deposit')->nullable();
            $table->integer('extra_fee')->nullable();
            $table->boolean('final_paid')->default(false);
            $table->date('engaged_date')->nullable();
            $table->date('married_date')->nullable();
            $table->text('remark')->nullable();
            $table->boolean('card_required')->default(false);
            $table->boolean('wood_required')->default(false);
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('order_statuses')->onDelete('cascade');
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
