<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('register/confirm', 'Auth\RegistrationConfirmationController@store');

Route::get('/home', 'HomeController@index');

Route::get('threads', 'ThreadsController@index')->name('threads.index');
Route::get('threads/create', 'ThreadsController@create')->middleware('email-confirmed');
Route::get('threads/{channel}', 'ThreadsController@index');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show');
Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::post('threads', 'ThreadsController@store')->middleware('email-confirmed');

Route::post('replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy');

Route::get('threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::patch('replies/{reply}', 'RepliesController@update');
Route::delete('replies/{reply}', 'RepliesController@destroy');
Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store');	

Route::post('threads/{channel}/{thread}/subscriptions', 'ThreadsSubscriptionsController@store')->middleware('auth');
Route::delete('threads/{channel}/{thread}/subscriptions', 'ThreadsSubscriptionsController@destroy')->middleware('auth');

Route::get('profiles/{user}', 'ProfilesController@show');

Route::get('profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

Route::get('api/users', 'Api\UsersController@index');
Route::post('api/users/{id}/avatar', 'Api\UserAvatarController@store');
