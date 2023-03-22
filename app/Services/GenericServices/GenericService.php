<?php

namespace App\Services\GenericServices;
use Illuminate\Support\Facades\Http;

abstract class GenericService {

    function __construct(protected string $baseUrl, protected string $token)
    {
    }

    /**
     * Construct the request to the API
     * @param string $route Route to send the request
     * @param array $data Query data to send
     * @param string $method GET, POST, PUT, DELETE
     */
    protected function sendRequest($route = '', $authType = 'Bearer', $data = [],  $method = 'get'){
		if(!$route){
			return false;
		}

		$urlApi = $this->baseUrl.$route;

        $response = Http::withToken($this->token, $authType)
            ->withoutVerifying()
            ->withOptions([
                'verify' => false
            ])
            ->send($method, $urlApi, [
                'query' => $data
            ]);
		
		if (!$response) {
			return false;
		}
		
        return $response->json();
	}
}

