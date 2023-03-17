<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WpOkboxService {
	private string $urlBase;
	private string $token;

    function __construct(){
		if(env('APP_ENV') == 'local'){
			$this->urlBase = config('apis.wordpress.dev_base_url');
			$this->token = base64_encode(env('DEV_API_TOKEN_LEADS', ""));
		}
		else{
			$this->urlBase = config('apis.wordpress.base_url');
			$this->token = base64_encode(env('PROD_API_TOKEN_LEADS', ""));
		}
	}

    /**
     * Get the statistics of the center from the API
     * @param string $variable Variable to get the statistics
     */
    function getStats(string $variable = '') {
        $route = 'statistics/';
        switch($variable){
			case 'dia' || 'dia-centro':
				$route .= 'day-center';
                break;
			case 'anyo-mes-centro':
				$route .= 'month-year-center';
                break;
			default:
                $route .= 'month-year-center';
                break;
		}

        return $this->sendRequest($route);
    }

    /**
     * Construct the request to the API
     * @param string $route Route to send the request
     * @param array $data Query data to send
     * @param string $method GET, POST, PUT, DELETE
     */
    protected function sendRequest($route = '', $data = [],  $method = 'get'){
		if(!$route){
			return false;
		}

		$urlApi = $this->urlBase.$route;

        $response = Http::withToken($this->token)
            ->withoutVerifying()
            ->send($method, $urlApi, [
                'query' => $data
            ]);
		
		if (!$response) {
			return false;
		}
		
        return $response->json();
	}
}