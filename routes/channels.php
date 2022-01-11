<?php

use App\Models\ChatroomUser;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat.{chatroom_id}', function ($user, $chatroom_id) {

    return ChatroomUser::where('user_id', $user->id)
        ->where('chatroom_id', $chatroom_id)
        ->first();
});


Broadcast::channel('chatrooms.{message_to}', function ($user, $message_to) {
    return true;
});
