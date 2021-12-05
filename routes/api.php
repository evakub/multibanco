<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

use App\Jobs\setTransactionPendingJob;
use App\Services\NuvemService;

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

/*

{
	
	"payment_provider_id": "38b772ec-70c4-4477-b4c8-cdf3e2f58a69",
	"payment_method" : {
		"type" : "wire_transfer",
		"id" : "link"
	},
	"first_event": {
	    "amount": {
	      "value": "14.20",
	      "currency": "EUR"
	    },
	    "type": "sale",
	    "status": "pending",
	    "happened_at": "2021-12-02T18:20:15Z"
    },
     "info": {
	    "external_id": "508136217",
	    "external_url": "https://gateway.ifthenpay.com/?k=RUdBUy0zMTkxOTM1MDgxMzYyMTcxNC4yMjAyMTEyMDMyMTE4NDY%3D"
	}
}

*/
Route::post('/payment', function(Request $request) {


    $response_ifthenpay_url = Http::post('https://ifthenpay.com/api/gateway/paybylink/EGAS-319193', [
        "id"     => $request["id"],
        "amount" => $request["amount"]
    ])->json();
    $request['redirect_url'] = $response_ifthenpay_url;


	//setTransactionPendingJob::dispatch($request->all());//->delay(now()->addSeconds(10));
	$nuvemService = new NuvemService();
	$response_nuvem = json_decode($nuvemService->setOrderPending($request["id"], $request["amount"], $request["redirect_url"])["response"]);
	
	//print_r($response_nuvem);

    return json_encode([ "redirect_url" => $response_ifthenpay_url]);

  })->name('payment');



Route::get('/callback', function(Request $request) {
    

    return json_encode([ "callback_url" => $request]);

  })->name('callback');