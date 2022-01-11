<?php
namespace Database\Seeders;

use App\Models\Chatroom;
use App\Models\Conversation;
use Illuminate\Database\Seeder;

class ChatroomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Chatroom::create()->chatroomUser()->createMany([
            ['user_id'=>1],
            ['user_id'=>2],
        ]);

        Chatroom::create()->chatroomUser()->createMany([
            ['user_id'=>1],
            ['user_id'=>3],
        ]);
    }
}
