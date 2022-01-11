<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatroomUser extends Model
{
    protected $fillable = [
        "user_id",
        "chatroom_id"
    ];

    public function users()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
