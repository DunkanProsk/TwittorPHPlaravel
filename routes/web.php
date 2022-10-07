<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\MainController@home')->name('home');

Route::post('/', 'App\Http\Controllers\MainController@home')->name('home');

Route::get('/signin', 'App\Http\Controllers\MainController@signin')->name('signin');

Route::post('/signin/check', 'App\Http\Controllers\MainController@signin_check');

Route::get('/loginout', 'App\Http\Controllers\MainController@loginout')->name('loginout');

Route::get('/loginout_check', 'App\Http\Controllers\MainController@loginout_check')->name('loginout_check');

Route::get('/signup', 'App\Http\Controllers\MainController@signup');

Route::post('/signup/check', 'App\Http\Controllers\MainController@signup_check');

Route::get('/newtweet', 'App\Http\Controllers\MainController@newTweet');

Route::post('/newtweet/create', 'App\Http\Controllers\MainController@createNewTweet');

Route::get('/tweetDelete', 'App\Http\Controllers\MainController@deleteTweet');

Route::post('/retweet', ['App\Http\Controllers\MainController', 'newRetweet']);

Route::post('/create_retweet', ['App\Http\Controllers\MainController', 'createRetweet']);

Route::get('/hashtag', ['App\Http\Controllers\MainController' , 'hashtags']);

Route::get('/{nickname}', ['App\Http\Controllers\MainController' , 'userProfile'])->name('user');

Route::post('/{nickname}/follow', ['App\Http\Controllers\MainController' , 'userFollow']);

Route::post('/{nickname}/unfollow', ['App\Http\Controllers\MainController' , 'userUnfollow']);
