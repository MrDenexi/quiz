<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/quiz', 'QuizController@index')->name('quiz.index');
Route::get('/quiz/{id}/{token?}', 'QuizController@show')->name('quiz.show');

Route::patch('/session-question/{id}', 'QuizController@submitAnswer')->name('quiz.submitAnswer');

