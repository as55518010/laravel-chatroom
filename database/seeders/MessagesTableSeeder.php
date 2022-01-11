<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $create = [
            [
                'body' => '嗨',
                'user_id' => 1,
                'chatroom_id' => 1,
            ],
            [
                'body' => '你好',
                'user_id' => 2,
                'chatroom_id' => 1,
            ],
            [
                'body' => '你好 One',
                'user_id' => 1,
                'chatroom_id' => 2,
            ],
            [
                'body' => '你好 One',
                'user_id' => 1,
                'chatroom_id' => 2,
            ],
            [
                'body' => '你好 Three',
                'user_id' => 3,
                'chatroom_id' => 2,
            ]
        ];
        foreach ($create as $value) {
            Message::create($value);
        }
    }
}
