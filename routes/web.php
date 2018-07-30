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

Route::post('/webhooks/stripe', 'WebhookController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/orders', 'OrderController@index')->name('orders.index');
Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');

Route::get('/products/{product}', 'ProductController@show')->name('products.show');

Route::post('/products/{product}/purchase', 'ProductPurchaseController@store')->name('products.purchase');
