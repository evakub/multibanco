<?php

namespace App\Services;

use App\Traits\ConsumesExternalServices;
use App\Traits\InteractWithExternalServices;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

class NuvemService
{
    use ConsumesExternalServices, InteractWithExternalServices;
    /**
     * The base url to send requests
     * @var string
     */
    protected $baseUri;
	protected $paymentProviderId;


    public function __construct()
    {
        $this->baseUri = config('services.nuvem.base_uri');
		$this->paymentProviderId = config('services.nuvem.payment_provider_id');
		
    }
    
    /**
     * Resolve to autenticate before send the request
     * @return void
     */
    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $accessToken = config('services.nuvem.token');
        $headers['Authentication'] = $accessToken;
        return $headers;
    }


    public function setOrderPending($orderId, $orderValue, $redirectUrl)
    {   

		$storeId = '1911491'; // 1950502 = r2store
		
        $url = $storeId.'/orders/'.$orderId.'/transactions';

		$happened_at  = new DateTime();
		

        $queryParams = [
			"payment_provider_id" => $this->paymentProviderId,
			"payment_method" => [
				"type"  => "wire_transfer",
				"id"  => "link",
			],
			"info" => [
				"external_id" => strval($orderId),
				"external_url" => strval($redirectUrl)
			],
			"first_event" => [
				"amount" => [
					"value" => strval($orderValue),
					"currency" => "EUR"
				],
				"type" => "sale",
				"status" => "pending",
				"happened_at" => $happened_at->format('c')
			]
        ];

        //Log::notice($queryParams);
        $response = $this->makeRequest('POST', $url, [], $queryParams, [
            'Content-Type' => 'application/json',
             'Accept' => 'application/json',
             'Origin' => 'https://lolja.pt',
            ]
        );
        Transaction::create([
            'request_payload' => json_encode($queryParams),
            'type' => 'pending',
            'payload' => json_encode($response),
        ]);
        return $response; // remove?
    }


    public function setOrderPaid($orderId, $orderValue, $redirectUrl)
    {   

		$storeId = '1911491'; // 1950502 = r2store
		
        $url = $storeId.'/orders/'.strval($orderId).'/transactions';

		$happened_at  = new DateTime();
		

        $queryParams = [
			"payment_provider_id" => $this->paymentProviderId,
			"payment_method" => [
				"type"  => "wire_transfer",
				"id"  => "link",
			],
			"info" => [
				"external_id" => strval($orderId),
				"external_url" => strval($redirectUrl)
			],
			"first_event" => [
				"amount" => [
					"value" => strval($orderValue),
					"currency" => "EUR"
				],
				"type" => "sale",
				"status" => "success",
				"happened_at" => $happened_at->format('c')
			]
        ];

        //Log::notice($queryParams);
        $response =  $this->makeRequest('POST', $url, [], $queryParams, [
            'Content-Type' => 'application/json',
             'Accept' => 'application/json',
             'Origin' => 'https://lolja.pt',
            ]
        );
        Log::notice($response);
        Transaction::create([
            'request_payload' => json_encode($queryParams),
            'type' => 'success',
            'payload' => json_encode($response),
        ]);
        return $response; // remove?
    }

    public function getOrder($orderId)
    {  
        $storeId = '1911491'; // 1950502 = r2store
        $url =  $storeId.'/orders/'.strval($orderId);
        
        return $this->makeRequest('GET', $url, [], [], []);

    }

  


   
}