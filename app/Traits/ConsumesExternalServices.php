<?php

namespace App\Traits;
use GuzzleHttp\Client;

trait ConsumesExternalServices
{
    /**
     * Make a request to a service, receive a method, request url and params
     * @return stdClass/string
     */
    public function makeRequest($method, $requestUrl, $queryParams = [], 
        $formParams = [], $headers = [])
    {

       
        $client = new Client([
            'base_uri' => $this->baseUri,
            'debug' => false

        ]);
        
        $formParamsJson = json_encode($formParams);
        
       
        if(method_exists($this, 'resolveAuthorization')){
            $this->resolveAuthorization($queryParams, $formParams, $headers);
        }
        //print_r($formParamsJson);
        try {
            $response = $client->request($method, $requestUrl, [
                'query' => $queryParams,
                'headers' => $headers,
                'http_errors' => true,
                'body' => $formParamsJson,

            ]);
            
        } catch(\Exception $e) {
            return [
                'status_code' => 999,
                'response' => "Something is wrong with API",
                'erro' => $e
            ];
        }

        $status_code = $response->getStatusCode();
        $response = $response->getBody()->getContents();

        if(method_exists($this, 'decodeResponde')){
            $this->decodeResponde($response);
        }

        if(method_exists($this, 'checkErrorsResponse')){
            $this->checkErrorsResponse($response);
        }
        

        return ['status_code' => $status_code, 'response' => $response];
    }
}