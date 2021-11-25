<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middlhttps:\/\/gateway.ifthenpay.com\/url\/PpTNXF9QOJeware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/payment', function(Request $request) {
    //dd($request);
    $response = Http::post('https://ifthenpay.com/api/gateway/paybylink/EGAS-319193', [
        "id"     => $request["id"],
        "amount" => $request["amount"]
    ])->json();


    return json_encode([ "redirect_url" => $response]);

  })->name('payment');