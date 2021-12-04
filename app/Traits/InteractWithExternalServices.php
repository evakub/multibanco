<?php

namespace App\Traits;

trait InteractWithExternalServices
{
    /**
     * Decode the response, if necessary
     * @return void
     */

    public function decodeResponse($response)
    {
        $decodedResponse = json_decode($response);
        return $decodedResponse->data ?? $decodedResponse; 
    }

    /**
     * Check if exists errors in response
     * @return void
     */
    public function checkErrorsResponse($response)
    {
        if (isset($response->error))
        {
            throw new \Exception("Something is wrong: {$response->error}");
        }
    }
}