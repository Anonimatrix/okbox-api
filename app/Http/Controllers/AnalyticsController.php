<?php

namespace App\Http\Controllers;

use App\Http\Requests\DateRangeRequest;
use App\Http\Resources\Analytics\AnalyticResource;
use App\Http\Resources\SearchAnalytic\SearchAnalyticCollection;
use App\Http\Resources\Wp\WpResource;
use App\Services\GoogleServices\GoogleSearchConsoleService;
use Google\Service\AnalyticsReporting\DateRange;
use Google\Analytics\Data\V1beta\DateRange as DateRangeAnalytics;
use App\Services\GoogleServices\GoogleAnalyticsService;
use App\Services\WpOkboxService;
use ErrorException;
use Exception;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class AnalyticsController extends Controller
{
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
        $sites = config('apis.google-search.domains');

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
        $properties = config('apis.analytics.properties');

        foreach($properties as $property){
            //Add property name and data to analytics array
            $propertiesData[$property] = ['analytics' => $service->getAnalytics($property, $date), 'property' => $property];
        }

        return AnalyticResource::collection($propertiesData);
    }

    public function googleAds() 
    {
        return response()->json(['message' => 'Not implemented yet'], 501);
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
}
