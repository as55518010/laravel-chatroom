<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatroomController;
use App\Http\Controllers\MessagesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/user/register', [UserController::class, 'register']);


Route::post('/user/login', [UserController::class, 'login']);


Route::middleware(['jwt.auth'])->group(function () {

    Route::get('/user/info',               [UserController::class, 'userInfo']);
    Route::get('/user/find',               [UserController::class, 'findUser']);
    Route::get('/user/find/chatroom',      [UserController::class, 'findUserChatroom']);
    Route::post('/user/avatar',            [UserController::class, 'changeAvatar']);
    Route::post('/user/logout',            [UserController::class, 'logout']);
    Route::post('/user/refresh',           [UserController::class, 'refresh']);
    Route::put('/user/password',           [UserController::class, 'changePassword']);

    Route::apiResource('message',  MessageController::class);
    Route::apiResource('chatroom', ChatroomController::class);

});
