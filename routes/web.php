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
Route::middleware(['checkLanguage'])->group(function(){

	Route::get('/{lang}/{slug?}/{currPage?}', ["as" => "user", "uses" => "PageController@showUserWithLang"]);

	Route::get('/{slug?}/{currPage?}', ["as" => "lang.user", "uses" => "PageController@showUser"]);

	Route::get('/{lang}/admin/{slug?}', ['as' => "admin", "uses" => "PageController@showAdminWithLang"]);

	Route::get('/admin/{slug?}', ['as' => "lang.admin", "uses" => "PageController@showAdmin"]);

	Route::get('/{lang}/admin/cau-hinh/{slug}', ["as" => "config", "uses" => "PageController@showConfigWithLang"]);

	Route::get('/admin/cau-hinh/{slug}', ["as" => "lang.config", "uses" => "PageController@showConfig"]);

});

Route::post('/post', ["as" => "post", "uses" => "PageController@postData"]);


