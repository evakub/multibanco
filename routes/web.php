<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Services\NuvemService;

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

Route::get('/callback.php', function(Request $request) {

    //chave cdb6929a9dfcbf0301256830fe06b55d
    //https://payment.parceiroslolja.com/callback.php?key=[ANTI_PHISHING_KEY]&id=[ID]&amount=[AMOUNT]&payment_datetime=[PAYMENT_DATETIME]&payment_method=[PAYMENT_METHOD]
    
    if(isset($request['key']) && $request['key'] == 'cdb6929a9dfcbf0301256830fe06b55d'){
        $nuvemService = new NuvemService();
        $request["redirect_url"] = "https://lolja.pt";
        if(isset($request["id"]) && isset($request["amount"])){
            $response_nuvem = $nuvemService->setOrderPaid($request["id"], $request["amount"], $request["redirect_url"]);
            return json_encode([ "callback" => $response_nuvem]);
        }else{
            abort(400, 'Bad request');
        }
    }else{
        abort(400, 'Bad request');
    }

  })->name('callback');

