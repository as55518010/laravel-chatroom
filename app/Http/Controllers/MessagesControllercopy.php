<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\Message;
use App\Events\MessageSent;
use App\Models\Chatroom;
use Illuminate\Http\Request;
use App\Events\ChatroomEvent;
use App\Models\Chatroom_user;
use App\Http\Controllers\Controller;


class MessagesControllercopy extends Controller
{
    public function sendMessage(Request $request){
        $chatroom_id=$request->get('chatroom_id');
    	$message_to=$request->get('message_to');
    	$body=$request->get('body');
    	$user_id= JWTAuth::parseToken()->toUser()->id;
        $new = false;
        $message;

        if( $chatroom_id ){
        	$message= new Message([
        		'body' => $body,
        		'user_id' => $user_id,
        		'chatroom_id' => $chatroom_id
        	]);

        	$message->save();

        }else{
            $new_chatroom = new Chatroom();
            $new_chatroom->save();
            $chatroom_id=$new_chatroom->id;
            $new=true;

            $first_chatroom_user = new Chatroom_user([
                'user_id' => $message_to,
                'chatroom_id' => $new_chatroom->id
            ]);
            $first_chatroom_user->save();

            $second_chatroom_user = new Chatroom_user([
                'user_id' => $user_id,
                'chatroom_id' => $new_chatroom->id
            ]);
            $second_chatroom_user->save();



            $message= new Message([
                'body' => $body,
                'user_id' => $user_id,
                'chatroom_id' => $new_chatroom->id
            ]);

            $message->save();
        }


        broadcast(new MessageSent($message))->toOthers();
        broadcast(new ChatroomEvent($message_to))->toOthers();

        return response()->json(["chatroom_id"=>$chatroom_id,"new_chatroom" => $new],200);
    }
}
