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

//NOTE: This is 302 because we might want to add a welcome page at some point
Route::redirect('/', '/poll', 302);

Route::get('/poll', 'PollController');

Route::post('/poll', 'PollController@create');

Route::get('/poll/{poll}', 'PollController@view');

Route::get('/poll/{poll}/edit', 'PollController@admin');
Route::patch('/poll/{poll}', 'PollController@edit');

Route::post('/poll/{poll}/vote', 'PollController@vote');

Route::get('/poll/{poll}/results', 'PollController@viewResults');
