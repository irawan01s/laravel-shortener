<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//       return view('welcome');
// });

Route::get('/', 'ShortLinkController@index');
Route::post('shorten', 'ShortLinkController@store')->name('shorten.store');
Route::get('{shorten}', 'ShortLinkController@show')->name('shorten.show');
Route::delete('{shorten}', 'ShortLinkController@destroy')->name('shorten.destroy');
Route::get('{shorten}/stats', 'ShortLinkController@stats')->name('shorten.stats');
// Route::resource('shorten', 'ShortLinkController')->except(['create', 'update']);
