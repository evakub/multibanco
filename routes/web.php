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

Route::get('/configuration', function () {
    return view('configuration');
});

Route::get('/support', function () {
    return view('support');
});


Route::get('/rates', function () {
    return view('rates');
});

