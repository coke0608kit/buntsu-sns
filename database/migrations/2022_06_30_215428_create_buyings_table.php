<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('item')->comment('商品内容。singleQutte か setQutte');
            $table->integer('quantity')->comment('いくつ購入したか');
            $table->integer('totalPrice')->comment('総合計金額');
            $table->integer('done')->comment('未対応:0, 対応済み:1');
            $table->string('plan')->nullable()->comment('プラン名');
            $table->char('user_id', 26);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('buyings');
    }
}
