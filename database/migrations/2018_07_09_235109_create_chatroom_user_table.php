<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatroomUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatroom_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chatroom_id')->constrained('chatrooms')->comment('關聯聊天室表(chatroom)');
            $table->foreignId('user_id')->constrained('users')->comment('關聯用戶表(user)');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `chatroom_users` comment '聊天室用戶表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatroom_users');
    }
}
