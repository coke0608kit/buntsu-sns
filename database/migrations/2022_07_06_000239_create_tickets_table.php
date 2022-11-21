<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->char('from', 26)->nullable();
            $table->foreign('from')->references('id')->on('users')->onDelete('cascade');
            $table->char('to', 26)->nullable();
            $table->foreign('to')->references('id')->on('users')->onDelete('cascade');
            $table->integer('used')->default(0)->comment('未使用:0, 使用済み:1');
            $table->integer('done')->default(0)->comment('未対応:0, 対応済み:1');
            $table->integer('published')->default(0)->comment('未発行:0, 発行済み:1');
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
        Schema::dropIfExists('tickets');
    }
}
