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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

//Route::get('/', function () {
//    $identicon =  new \Identicon\Identicon();
//    $avatar=$identicon->getImageData('ceshi@163.com');
//    Storage::disk('public')->put('file.png', $avatar);
//
//});

Auth::routes();
Route::get('email/verify/{token}',['as'=>'email.verify','uses'=>'EmailController@verify']);
Route::get('email/remail/{name}',['as'=>'email.remail','uses'=>'EmailController@remail'])->middleware('throttle:2');;
Route::get('/user/sign','UserController@signup');

Route::get('/', 'HomeController@index')->name('home');
Route::group(['prefix'=>'/select'],function(){
    Route::get('/tag','TagController@selectTag');
    Route::get('/tag/{post_id}','TagController@selectPostTag');
    Route::get('/board','BoardController@selectBoard');
    Route::get('/user','UserController@selectUser');

});
Route::group(['prefix'=>'/post'],function(){
    Route::get('/{datetime}/{url}', 'PostController@show');
    Route::get('/{datetime}/{url}/closecomment/{token}', 'PostController@closeComment');
    Route::get('/{datetime}/{url}/opencomment/{token}', 'PostController@openComment');
    Route::get('/{datetime}/{url}/edit/{token}', 'PostController@edit');
    Route::get('/attentions', 'PostController@attentionShow');
    Route::get('delete/{id}/return', 'PostController@delPostReturn');
    Route::get('/new', 'PostController@create');
    Route::post('/', 'PostController@store');
    Route::patch('/{id}', 'PostController@update');
    Route::get('/{token}', 'PostController@personalPost');
    Route::post('/{id}/delete', 'PostController@destroy');
    Route::post('/{id}/close', 'PostController@destroy');
    Route::get('/attention/{datetime}/{url}/{token}','PostController@attentionPost');
    Route::get('/attentionoff/{datetime}/{url}/{token}','PostController@offPost');
    Route::post('/{id}/comment/{father_id?}','CommentController@addComment');
});


Route::post('/search', 'PostController@search')->name('search');



Route::get('/boards', 'BoardController@index')->name('board');
Route::get('/board/{board_url}', 'BoardController@boardList');
Route::get('/board/{board_url}/attentionoff', 'BoardController@offBoard');
Route::get('/board/{board_url}/attention', 'BoardController@attentionBoard');


Route::get('/setting/board','AuthController@authBoard');
Route::get('/setting/user','AuthController@authUsers');
Route::post('/setting/user/{id}','AuthController@banUsers');
Route::get('/setting/user/{id}/outban','AuthController@outBan');
Route::get('/setting/post','AuthController@authPosts');
Route::get('/setting/post/{id}/edit','AuthController@authPostsEdit');
Route::post('/setting/notify','AuthController@authNotify');
Route::get('/setting/board/{id}','AuthController@editBoard');
Route::post('/setting/board','AuthController@storeBoard');
Route::post('/setting/system','AuthController@systemSetting');
Route::patch('/setting/board/{id}','AuthController@updateBoard');
Route::delete('/setting/board/{id}','AuthController@destroyBoard');
Route::delete('/setting/tags/{id}','AuthController@destroyTag');
Route::get('setting/randpic','AuthController@randPic');

Route::get('/test',function(){
    $test = 'Nomo:Task:Admin_post:2:28';
    echo substr($test,23,2);

});


Route::get('/order/{datetime}/{url}/{token}','OrderController@buyPost');






