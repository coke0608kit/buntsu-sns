<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->string('delivery_id')->nullable()->unique()->comment('配達用ID');
            $table->string('name')->unique();
            $table->string('nickname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('payjp_customer_id')->nullable()->comment('payjp顧客ID');
            $table->string('plan')->nullable()->comment('プラン名');
            $table->string('planStatus')->nullable()->comment('通常契約:0, 解約月:1');
            $table->string('subId')->nullable()->comment('サブスク');
            $table->rememberToken();
            $table->timestamps();
            $table->string('official')->comment('公式アカウント 0: 非公式  1: 公式');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
