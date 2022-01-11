<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
                'name' => 'One',
                'email' => 'one@com.tw',
                'avatar' => '/img/user1.jpg',
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'Two',
                'email' => 'two@com.tw',
                'avatar' => '/img/user2.jpg',
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'Three',
                'email' => 'three@com.tw',
                'avatar' => '/img/user3.jpg',
                'password' => bcrypt('123456'),
            ]
        ];
        foreach ($create as $value) {
            User::create($value);
        }
    }
}
