<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseHasCookiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_has_cookies', function (Blueprint $table) {
            $table->integer('case_id', false, true);
            $table->integer('cookie_id', false, true);
            $table->integer('pack_id', false, true);
            $table->integer('amount')->nullable();;

            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->foreign('cookie_id')->references('id')->on('cookies')->onDelete('cascade');
            $table->foreign('pack_id')->references('id')->on('packs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_has_cookies');
    }
}
