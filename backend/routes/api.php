<?php

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
// Open routes
Route::post('login', 'AuthController@login')->name('login');
Route::post('register', 'AuthController@register');
Route::get('/get-invoice/{id}', 'RentBookController@getInvoice');
Route::group(['middleware' => ['auth:api']], function () {

    // Routes for both admin & renter user
    Route::get('/get-all-books', 'BookController@getAllBooks');
    Route::get('/count-all', 'BookController@countsAll');
    Route::get('/get-rented-book', 'RentBookController@getRentedBook');
    Route::post('/rent-book', 'RentBookController@rentBook');
    Route::put('/return-book/{id}', 'RentBookController@returnBook');

    // Admin user routes group
    Route::group(['name' => 'admin', 'middleware' => ['role:admin']], function () {
        Route::post('/add-new-book', 'BookController@addNewBook');
        Route::put('/update-book/{book_id}', 'BookController@updateBook');
        Route::delete('/delete-book/{book_id}', 'BookController@deleteBook');
    });

    // Renter user route group
    Route::group(['name' => 'renter', 'middleware' => ['role:renter']], function () {

    });

});
