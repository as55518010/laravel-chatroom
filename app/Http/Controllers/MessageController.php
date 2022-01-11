<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chatroom;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Events\ChatroomEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chatroom_id  = $request->get('chatroom_id');
        $message_to   = $request->get('message_to');
        $body         = $request->get('body');
        $user_id      = Auth::id();
        $new          = false;
        $chatroom_user = [];
        if ($request['chatroom_id']) {
            $message = Message::create([
                'body'        => $body,
                'user_id'     => $user_id,
                'chatroom_id' => $chatroom_id
            ]);
        } else {
            $new_chatroom = Chatroom::create();
            $chatroom_user = $new_chatroom->chatroomUser()->createMany([
                ["user_id" => $user_id],
                ["user_id" => $message_to],
            ])->where('user_id', '!=', $user_id)->load('users')->toArray();
            sort($chatroom_user);
            $message = $new_chatroom->messages()->create([
                'body' => $body,
                'user_id' => $user_id,
            ]);
            $chatroom_id = $new_chatroom->id;
            $new = true;
        }
        broadcast(new MessageSent($message))->toOthers();
        broadcast(new ChatroomEvent($message_to))->toOthers();
        return response()->json(["chatroom_id" => $chatroom_id, 'chatroom_user' => $chatroom_user, "new_chatroom" => $new]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
