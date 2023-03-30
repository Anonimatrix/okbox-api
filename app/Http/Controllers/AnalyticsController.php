<?php

namespace App\Http\Controllers;

use App\Http\Requests\DateRangeRequest;
use App\Http\Resources\Ads\GoogleAdsResource;
use App\Http\Resources\Analytics\AnalyticResource;
use App\Http\Resources\SearchAnalytic\SearchAnalyticCollection;
use App\Http\Resources\SpManager\SpManagerResource;
use App\Http\Resources\Wp\WpResource;
use App\Services\GoogleServices\GoogleAdsService;
use App\Services\GoogleServices\GoogleSearchConsoleService;
use App\Services\SpaceManagerService;
use Google\Service\AnalyticsReporting\DateRange;
use Google\Analytics\Data\V1beta\DateRange as DateRangeAnalytics;
use App\Services\GoogleServices\GoogleAnalyticsService;
use App\Services\WpOkboxService;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class AnalyticsController extends Controller
{
    protected int $page = 1;
    protected int $perPage = 10;

    function __construct()
    {
        $this->page = request()->input('page', 1);
        $this->perPage = request()->input('per_page', 10);
    }
    
    // Consume Google Search Console API and return data
    public function searchConsole(GoogleSearchConsoleService $service, DateRangeRequest $request) 
    {
        //Declare array to store data from sites
        $dataSites = [];

        //Construct date range
        $date = new DateRange(); 
        $date->setStartDate($request->input('start_date'));
        $date->setEndDate($request->input('end_date'));

        //Get sites domains to get analytics
        $sites = collect(config('apis.search-console.domains', []))
            ->forPage($this->page, $this->perPage);

        foreach($sites as $site){
            $dataSites[$site] = $service->getAnalytics($site, $date)->getRows();
        }

        return new SearchAnalyticCollection($dataSites);
    }

    //Consume Google Analytics API and return data
    public function googleAnalytics(GoogleAnalyticsService $service, DateRangeRequest $request) 
    {
        //Declare array to store data from sites
        $propertiesData = [];

        //Construct date range
        $date = new DateRangeAnalytics(); 
        $date->setStartDate($request->input('start_date'));
        $date->setEndDate($request->input('end_date'));

        //Get sites domains to get analytics
        $properties = collect(config('apis.analytics.properties', []))
            ->forPage($this->page, $this->perPage);

        foreach($properties as $property){
            //Add property name and data to analytics array
            $propertiesData[$property] = $service->getAnalytics($property, $date);
        }

        return AnalyticResource::collection($propertiesData);
    }

    public function googleAds(GoogleAdsService $service) 
    {
        $customer_ids = config('apis.google-ads.customer_ids');
        $analytics = [];
        foreach($customer_ids as $customer_id){
            $analytic = $service->getAnalytics($customer_id);
            array_push($analytics, ['analytics' => (object) $analytic, 'customer_id' => $customer_id]);
        }

        return new GoogleAdsResource($analytics);
    }

    public function wp(WpOkboxService $service) 
    {
        $data = $service->getStats();

        //Check if there is an error in the response
        if(isset($data['data']) && $data['data']['status'] >= 400 && $data['data']['status'] < 500){
            throw new InvalidResourceException("Unknown error getting wordpress data");
        }
            
        return new WpResource((object) $data);
    }

    public function spManager(SpaceManagerService $spaceManagerService)
    {
        $data = $spaceManagerService->getStats();

        return new SpManagerResource((object) $data);
    }
}
