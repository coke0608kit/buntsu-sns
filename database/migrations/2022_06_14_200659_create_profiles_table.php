<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('icon')->nullable();
            $table->integer('gender')->nullable()->comment('性別コード');
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->integer('day')->nullable();
            $table->text('zipcode1')->nullable()->comment('郵便番号前半3文字');
            $table->text('zipcode2')->nullable()->comment('郵便番号後半4文字');
            $table->text('pref')->nullable()->comment('都都道府県');
            $table->text('address1')->nullable()->comment('郵便番号自動入力の部分');
            $table->text('address2')->nullable();
            $table->string('realname')->nullable();
            $table->text('profile')->nullable()->comment('自由文');
            $table->text('canSendGender')->nullable();
            $table->boolean('status')->nullable()->comment('募集停止:0, 募集中:1');
            $table->boolean('condition')->nullable()->comment('てきぱき:0, まったり:1');
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
        Schema::dropIfExists('profiles');
    }
}
