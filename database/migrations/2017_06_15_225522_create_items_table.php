<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('company');
            $table->string('speaker');
            $table->text('description');
            $table->boolean('event_open')->default(false)->commnet('출석 이벤트 시작여부');
            $table->unsignedTinyInteger('event_rank')->nullable()->comment('당첨 순번');
            $table->string('event_winner')->nullable()->commnet('출석 이벤트 당첨자');
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
        Schema::dropIfExists('items');
    }
}
