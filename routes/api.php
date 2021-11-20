<?php

use Illuminate\Http\Request;

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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::post('user/register', 'Api\UserController@register');
Route::post('user/login',    'Api\UserController@authenticate');

Route::group(['middleware' => ['jwt.verify']], function() {

    Route::post('user','Api\UserController@getAuthenticatedUser');

    Route::get('subject/{id}', 'Api\SubjectController@get');
    Route::get('subject', 'Api\SubjectController@list');
    Route::post('subject',     'Api\SubjectController@create');
    Route::put('subject',      'Api\SubjectController@update');
    Route::delete('subject',   'Api\SubjectController@delete');

    Route::get('note/{id}', 'Api\SubjectNoteController@get');
    Route::get('note/list/{id_subject}', 'Api\SubjectNoteController@list');
    Route::post('note',     'Api\SubjectNoteController@create');
    Route::put('note',      'Api\SubjectNoteController@update');
    Route::delete('note',   'Api\SubjectNoteController@delete');

});
