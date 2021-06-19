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

Route::group(['prefix' => 'auth'], function () {
    Route::post('register','AuthController@register');
    Route::post('login','AuthController@login');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout','AuthController@logout');
        Route::get('profile','AuthController@profile');
       
    });
 

});
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/books','BookController@index');
    
    
    Route::post('/book/add','BookController@store');
    Route::post('/book/update','BookController@update');
    Route::get('/book/{id}/delete','BookController@destroy');

    Route::get('/commentaire/{id}/delete','CommentaireController@destroy');
    Route::get('/book/{id}/comments','CommentaireController@getBookComments');
    Route::get('/users','CommentaireController@getUsers');
    Route::get('/user/{id}/delete','AuthController@destroy');
    Route::post('/user/update','AuthController@Update');
    Route::post('/user/pw/update','AuthController@passwordChange');

    Route::post('/post/add','PostController@store');
    Route::post('/post/update','PostController@update');
    Route::get('/my/posts','PostController@getMyPosts');
    Route::get('/posts','PostController@index');
    Route::get('/post/{id}/delete','PostController@destroy');

    Route::post('/comment/add','CommentaireController@store');
    Route::post('/comment/update','CommentaireController@update');
    



});
Route::get('/books/audio','BookController@getAudioBooks');
Route::get('/books/pdf','BookController@getpdfBooks');
Route::post('/books/audio/search','BookController@getAudioBooksSearch');
Route::post('/books/pdf/search','BookController@getPDFBooksSearch');

Route::get('/book/{id}/show','BookController@show');