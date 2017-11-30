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


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('datatable/getdata','SchedulesController@index');
Route::get('datatable/getdata', 'SchedulesController@getPosts')->name('datatable/getdata');

Route::get('timeclock', 'TimeClock@index')->name('timeclock');

Route::post('timeclock_salida', 'TimeClock@store')->name('checar_salida');

Route::post('timeclock_comida', 'TimeClock@guardarComida')->name('checar_comida');

Route::post('timeclock_regresoComida', 'TimeClock@guardarRegresoComida')->name('checar_regresoComida');

Route::post('timeclock_statusComida', 'TimeClock@comprobarComida')->name('status_comida');

Route::get('prueba', 'PermitsController@getPosts')->name('permits');

Route::get('permits', 'PermitsController@index');
Route::get('datatable/permits', 'PermitsController@getPosts')->name('permits');

Route::post('permits_create', 'PermitsController@store')->name('createPermits');



//Route::get('datatable/permits', 'PermitsController@store')->name('createPermits');