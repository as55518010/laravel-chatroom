<?php

namespace App\Models;

use App\Models\User;
use App\Models\Message;
use App\Models\ChatroomUser;
use Illuminate\Database\Eloquent\Model;

class Chatroom extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class,'users');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    public function chatroomUser()
    {
        return $this->hasMany(ChatroomUser::class);
    }
}
