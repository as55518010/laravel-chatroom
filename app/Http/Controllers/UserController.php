<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        try {
            if (!$token = Auth::attempt($credentials)) {
                return response()->json(['error' => '未經授權'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'server異常'], 500);
        }
        return response()->json($token);
    }


    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'avatar' => '/img/user1.jpg'
        ]);

        return response()->json([
            "message" => "用戶創建了"
        ], 201);
    }


    public function userInfo()
    {
        $user = Auth::user();
        return response()->json($user, 200);
    }


    public function findUser(Request $request)
    {
        $keyword = $request->get('keyword');

        $users = User::where('name', 'LIKE', "%{$keyword}%")
            ->where('id', '!=', Auth::id())
            ->get();

        return response()->json(['data' => $users], 200);
    }


    public function findUserChatroom(Request $request)
    {
        $user_id = Auth::id();
        $other_user_id = $request->get('id');

        $chatroom = User::find($user_id)
            ->chatroom()
            ->with(['chatroomUser' => function ($query) use ($user_id) {
                $query->where('user_id', '!=', $user_id)->with('users');
            }, 'messages'])
            ->whereHas('chatroomUser', function ($query) use ($other_user_id) {
                $query->where('user_id', $other_user_id);
            })->first();
        return response()->json(['data' => $chatroom], 200);
    }


    public function changeAvatar(Request $request)
    {
        $avatarfile = $request->file('avatar');

        $path = Storage::disk('public')->put("avatars", $avatarfile);

        $user = auth::user();
        $user->update([
            'avatar' => Storage::url($path)
        ]);
        return response()->json(
            [
                'message' => '頭像改變了',
                'new_path' => $user->refresh()->avatar
            ]
        );
    }

    public function changePassword(Request $request)
    {
        $input_password = $request->get('password');
        $new_password = $request->get('new_password');

        $this->validate($request, [
            'password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6',
        ]);

        $user = Auth::user();
        if (Hash::check($input_password, $user->password)) {
            $user->password = bcrypt($new_password);
            $user->save();
            return response()->json([
                'message'    => '更新密碼成功'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message'    => '密碼錯誤'
            ], 401);
        }
    }
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => '成功退出']);
    }
    public function refresh()
    {
        return response()->json([
            'message' => '刷新令牌成功',
            'result'    => Auth::refresh()
        ]);
    }
}
