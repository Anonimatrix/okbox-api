<?php

namespace App\Http\Controllers;

use App\Http\Requests\DateRangeRequest;
use App\Http\Resources\Analytics\AnalyticResource;
use App\Http\Resources\SearchAnalytic\SearchAnalyticCollection;
use App\Services\GoogleServices\GoogleSearchConsoleService;
use Google\Service\AnalyticsReporting\DateRange;
use Google\Analytics\Data\V1beta\DateRange as DateRangeAnalytics;
use App\Services\GoogleServices\GoogleAnalyticsService;
use Illuminate\Support\Facades\Config;

class AnalyticsController extends Controller
{
    public function searchConsole(GoogleSearchConsoleService $service, DateRangeRequest $request) 
    {
        //Declare array to store data from sites
        $dataSites = [];

        //Construct date range
        $date = new DateRange(); 
        $date->setStartDate($request->input('start_date'));
        $date->setEndDate($request->input('end_date'));

        //Get sites domains to get analytics
        $sites = Config::get('apis.google-search.domains');

        foreach($sites as $site){
            $dataSites[$site] = $service->getAnalytics($site, $date)->getRows();
        }

        return new SearchAnalyticCollection($dataSites);
    }

    public function googleAnalytics(GoogleAnalyticsService $service, DateRangeRequest $request) 
    {
        //Declare array to store data from sites
        $propertiesData = [];

        //Construct date range
        $date = new DateRangeAnalytics(); 
        $date->setStartDate($request->input('start_date'));
        $date->setEndDate($request->input('end_date'));

        //Get sites domains to get analytics
        $properties = Config::get('apis.analytics.properties');

        foreach($properties as $property){
            //Add property name and data to analytics array
            $propertiesData[$property] = ['analytics' => $service->getAnalytics($property, $date), 'property' => $property];
        }

        return AnalyticResource::collection($propertiesData);
    }
}
