<?php

namespace App\Services;

use App\Traits\ConsumesExternalServices;
use App\Traits\InteractWithExternalServices;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;

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
		//print_r(array( "accessToken" => $accessToken));
        $headers['Authentication'] = $accessToken;
        //$headers['Authorization'] = $accessToken;
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
        return $this->makeRequest('POST', $url, [], $queryParams, [
            'Content-Type' => 'application/json',
             'Accept' => 'application/json',
             'Origin' => 'https://r2store.lojavirtualnuvem.com.br',
            ]
        );
    }

    public function getOrder($orderId)
    {  
        $storeId = '1911491'; // 1950502 = r2store
        $url =  $storeId.'/orders/'.strval($orderId);
        
        return $this->makeRequest('GET', $url, [], [], []);

    }

  


   
}