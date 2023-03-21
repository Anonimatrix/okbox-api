<?php

namespace App\Services;

use App\Services\GenericServices\GenericService;
use Illuminate\Support\Facades\Http;

class WpOkboxService extends GenericService{

    function __construct(){
        $urlBase = "";
        $token = "";
		if(env('APP_ENV') == 'local'){
			$urlBase = config('apis.wordpress.dev_base_url');
			$token = base64_encode(env('DEV_API_TOKEN_LEADS', ""));
		}
		else{
			$urlBase = config('apis.wordpress.base_url');
			$token = base64_encode(env('PROD_API_TOKEN_LEADS', ""));
		}

        parent::__construct($urlBase, $token);
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
}