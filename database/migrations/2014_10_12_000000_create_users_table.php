<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * 用戶表
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('暱稱');
            $table->string('email')->unique()->comment('信箱');
            $table->string('password')->comment('密碼');
            $table->string('avatar')->default("/img/default.png")->comment('頭像');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `users` comment '用戶表'");
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
