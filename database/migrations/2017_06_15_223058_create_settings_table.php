<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', ['ready', 'open', 'close', 'result'])->default('ready')->comment('진행 상태');
            $table->unsignedSmallInteger('supply')->comment('개일별 지급 J-COIN');
            $table->unsignedInteger('capital')->comment('실제 투자액');
            $table->text('experts')->nullable()->comment('전문가 아이디');
            $table->unsignedTinyInteger('ratio')->default(50)->comment('전문가 투자배수');
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
        Schema::dropIfExists('settings');
    }
}
