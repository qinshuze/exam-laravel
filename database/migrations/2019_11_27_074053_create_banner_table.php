<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type')->comment('轮播图类型');
            $table->string('path', 255)->comment('轮播图路径');
            $table->bigInteger('weight')->comment('权重');
            $table->bigInteger('width')->nullable()->comment('轮播图宽');
            $table->bigInteger('height')->nullable()->comment('轮播图高');
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
        Schema::dropIfExists('banner');
    }
}
