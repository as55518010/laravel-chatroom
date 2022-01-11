<?php

namespace App\Models;

use App\Models\Message;
use App\Models\Chatroom;
use App\Models\ChatroomUser;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements CanResetPassword, JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }


    public function chatroom()
    {
        return $this->belongsToMany(Chatroom::class, 'chatroom_users');
    }

    public function chatroomUser()
    {
        return $this->hasMany(ChatroomUser::class);
    }
}
