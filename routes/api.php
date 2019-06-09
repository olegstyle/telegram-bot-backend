<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'PassportController@login');
    Route::post('register', 'PassportController@register');
});

Route::middleware('auth:api')->get('users/current', 'PassportController@user');

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'bots'], function () {
        Route::get('/', 'BotController@getBots');
        Route::get('/{bot}', 'BotController@getBot');
        Route::post('/{bot}', 'BotController@createBot');
        Route::delete('/{bot}', 'BotController@deleteBot');
    });

    Route::group(['prefix' => 'bots/chats'], function () {
        Route::get('/', 'BotController@getBotChats');
        Route::get('/{botChat}', 'BotController@getBotChat');
        Route::post('/', 'BotController@createBotChat');
        Route::put('/{botChat}', 'BotController@updateBotChat');
        Route::delete('/{botChat}', 'BotController@deleteBotChat');
    });

    Route::group(['prefix' => 'posts'], function () {
        Route::get('/', 'PostController@all');
        Route::get('/{post}', 'PostController@get');
        Route::post('/', 'PostController@create');
        Route::put('/{post}', 'PostController@update');
        Route::delete('/{post}', 'PostController@delete');
    });

    Route::group(['prefix' => 'schedules'], function () {
        Route::get('/', 'ScheduleController@all');
        Route::get('/{schedule}', 'ScheduleController@get');
        Route::post('/', 'ScheduleController@create');
        Route::put('/{schedule}', 'ScheduleController@update');
        Route::delete('/{schedule}', 'ScheduleController@delete');
    });
});
