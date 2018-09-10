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
            $table->string('name', 100)->nullable();
            $table->string('name_backup', 100)->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('phone_backup', 100)->nullable();
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
