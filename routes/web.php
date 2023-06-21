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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('units', 'UnitController');
Route::get('workers/all', 'WorkerController@all')->name('workers.all');
Route::get('workers/byrole/{id}', 'WorkerController@byrole')->name('workers.byrole');
Route::resource('workers', 'WorkerController');
Route::get('stores/all', 'StoreController@all')->name('stores.all');
Route::get('stores/users/{store}', 'StoreController@getUsers')->name('stores.getUsers');
Route::resource('stores', 'StoreController');
Route::resource('users', 'UserController');

Route::get('categories/children/{parent}', 'CategoryController@children')->name('categories.children');
Route::get('categories/parent/{parent}', 'CategoryController@parent')->name('categories.parent');
Route::get('categories/parents', 'CategoryController@parents')->name('categories.parents');
Route::get('categories/all', 'CategoryController@allCategories')->name('categories.all');
Route::resource('categories', 'CategoryController');

Route::get('products/all', 'ProductController@all')->name('products.all');
Route::get('products/bycategory/{id}', 'ProductController@bycategory')->name('products.bycategory');
Route::get('products/categories/{product}', 'ProductController@getCategories')->name('products.getCategories');
Route::resource('products', 'ProductController');

Route::get('roles/children/{parent}', 'RoleController@children')->name('roles.children');
Route::get('roles/parent/{parent}', 'RoleController@parent')->name('roles.parent');
Route::get('roles/parents', 'RoleController@parents')->name('roles.parents');
Route::get('roles/all', 'RoleController@allCategories')->name('roles.all');
Route::resource('roles', 'RoleController');

Route::get('parts/children/{parent}', 'PartController@children')->name('parts.children');
Route::get('parts/parent/{parent}', 'PartController@parent')->name('parts.parent');
Route::get('parts/parents', 'PartController@parents')->name('parts.parents');
Route::get('parts/all', 'PartController@allCategories')->name('parts.all');
Route::resource('parts', 'PartController');

Route::get('entries/codes', 'EntryController@codes')->name('entries.codes');
Route::get('entries/print/{id}', 'EntryController@print')->name('entries.print');
Route::resource('entries', 'EntryController');

Route::get('outputs/print/{id}', 'OutputController@print')->name('entries.print');
Route::resource('outputs', 'OutputController');



//                       <<<<<<<<<<-------------------- Reports Routes -------------------->>>>>>>>>>

Route::get('reports', 'ReportController@index')->name('reports.index');

Route::get('reports/entries', 'ReportController@entries')->name('reports.entries');
Route::get('reports/outputs', 'ReportController@outputs')->name('reports.outputs');
Route::get('reports/stocks', 'ReportController@stocks')->name('reports.stocks');
Route::get('reports/records', 'ReportController@records')->name('reports.records');

Route::post('reports/entries/show', 'ReportController@entries_show')->name('reports.entries.show');
Route::post('reports/outputs/show', 'ReportController@outputs_show')->name('reports.outputs.show');
Route::post('reports/stocks/show', 'ReportController@stocks_show')->name('reports.stocks.show');
Route::post('reports/records/show', 'ReportController@records_show')->name('reports.records.show');
Route::post('reports/specials/show', 'ReportController@specials_show')->name('reports.specials.show');

Route::post('reports/entries/print', 'ReportController@entries_print')->name('reports.entries.print');

Route::post('reports/outputs/print', 'ReportController@outputs_print')->name('reports.outputs.print');


