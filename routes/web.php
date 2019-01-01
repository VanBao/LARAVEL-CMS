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

	Route::get('/{lang}/admin/{slug?}/{currPage?}', ['as' => "lang.admin", "uses" => "PageController@showAdmin"]);

	Route::post('/{lang}/admin/{slug?}/{currPage?}', ['as' => "lang.admin.post", "uses" => "PageController@showAdmin"]);

	Route::get('/{lang}/admin/cau-hinh/{slug?}', ["as" => "lang.config", "uses" => "PageController@showConfig"]);

	Route::post('/{lang}/admin/cau-hinh/{slug?}', ["as" => "lang.config.post", "uses" => "PageController@showConfig"]);

	Route::get('/admin/cau-hinh/{slug?}', ["as" => "config", "uses" => "PageController@showConfig"]);

	Route::post('/admin/cau-hinh/{slug?}', ["as" => "config.post", "uses" => "PageController@showConfig"]);

	Route::get('/admin/{slug?}/{currPage?}', ['as' => "admin", "uses" => "PageController@showAdmin"]);

	Route::post('/admin/{slug?}/{currPage?}', ['as' => "admin.post", "uses" => "PageController@showAdmin"]);

	Route::get('/{lang}/{slug?}/{currPage?}', ["as" => "lang.user", "uses" => "PageController@showUser"]);

	Route::get('/{slug?}/{currPage?}', ["as" => "user", "uses" => "PageController@showUser"]);

});

Route::post('/post', ["as" => "post", "uses" => "PageController@postData"]);

Route::get('/admin/logout', ['as' => 'admin.logout', 'uses' => 'PageController@logoutAdmin']);

Route::get('/admin/sitemap', ['as' => 'admin.sitemap', 'uses' => 'PageController@generateSitemap']);


