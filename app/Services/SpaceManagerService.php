<?php

namespace App\Services;
use App\Services\GenericServices\GenericService;

class SpaceManagerService extends GenericService
{
    function __construct()
    {
        $baseUrl = "";
        if(env('APP_ENV') == 'local'){
			$baseUrl = config('apis.space-manager.dev_base_url');
		}
		else{
			$baseUrl = config('apis.space-manager.base_url');
		}

        $user = env('SPACE_MANAGER_API_USER', "");
        $password = env('SPACE_MANAGER_API_PASSWORD', "");

        $token = base64_encode($user.":".$password);

        parent::__construct($baseUrl, $token);
    }

    function getStats()
    {
        return [
            'centers' => $this->mapCenters(),
        ];
    }

    function mapCenters()
    {
        //Map all centers and return them with sizes and boxes
        return collect($this->getCenters())
            ->map(fn ($center) => (
                array_merge(
                    $center, 
                    [
                        'sizes' => $this->getAvailableSizes($center['siteid']),
                        'boxes' => $this->getAvailableBoxes($center['siteid'])
                    ]
                )
            ));
    }

    //Get all centers
	private function getCenters($nameSite = null, $postCode = null){
		if( ($postCode) and ($nameSite) ){
			$data = [];
			$data['SSiteName'] = $nameSite;
			$data['Spostcode'] = $postCode;
			
			$res = $this->sendRequest('WGetSiteDetails', 'Basic', $data);
		}
		else{
			$res = $this->sendRequest('WGetSiteDetails', 'Basic');
		}
		
		return $res;
	}
	
	
	// Get the center by id
	private function getCenterById($centerId){
		$centers = $this->sendRequest('WGetSiteDetails', 'Basic');
        $selectedCenter = null;
		
		foreach($centers as $center){
			if($center->siteid == $centerId){
				$selectedCenter = $center;
				break;
			}
		}
		
		if($selectedCenter){
			return $selectedCenter;
		}

        return false;
	}
	

	/**
     * Get all sizes of Space Manager
     */    
	private function getAvailableSizes($idSite){
		
		if(!$idSite){
            return false;
        }
			
        $data['isite'] = $idSite;
    
        $res = $this->sendRequest('WGetAvailableSizes', 'Basic', $data); 
        
        return $res;
	}
	
    /**
     * Returns the list of available boxes
     * @param int $idSite Id of the center
     * @param int $idSize Id of the size
     */
	private function getAvailableBoxes($idSite, $idSize = null){
		if(!$idSite)
        {
            return false;
        }

        $data['isite'] = $idSite;
        
        if($idSize){
            $data['iSize'] = $idSize;
        }
        
        $res = $this->sendRequest('WAvailableUnits', 'Basic', $data);		
        
        return $res;
	}
	
    /**
     * Returns the list of all contact types
     */
	private function getContactTypes($query = ''){
		$data['thetype'] = $query;
		
		$res = $this->sendRequest('WGetContactType', 'Basic', $data);
		
		if($res){
			return $res;
		}

        return false;
	}
}