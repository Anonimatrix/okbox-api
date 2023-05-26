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
use Illuminate\Http\Request;
use Symfony\Component\Translation\Exception\InvalidResourceException;

/**
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
*/
class AnalyticsController extends Controller
{
    protected int $page = 1;
    protected int $perPage = 3;

    function __construct()
    {
        $this->page = request()->input('page', $this->page);
        $this->perPage = request()->input('per_page', $this->perPage);
    }
    
    /**
     * @OA\Get(
     *     path="/api/search-console",
     *     summary="Consume Google Search Console API and return data",
     *     security={{"bearer_token":{}}},
     *     tags={"Analytics"},
     *     @OA\SecurityScheme(
     *        type="apiKey",
     *        in="header",
     *        name="Authorization"
     *     ),
     *     @OA\QueryParameter(
     *        name="start_date",
     *        required=true,
     *        description="Start date to get analytics"
     *     ),
     *     @OA\QueryParameter(
     *        name="end_date",
     *        required=true,
     *        description="End date to get analytics"
     *     ),
     *     @OA\QueryParameter(
     *        name="page",
     *        required=false,
     *        description="Page",
     *        example=1
     *     ),
     *     @OA\QueryParameter(
     *        name="per_page",
     *        required=false,
     *        description="Quantity of items per page",
     *        example=3
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function searchConsole(GoogleSearchConsoleService $service, DateRangeRequest $request) 
    {
        //Declare array to store data from sites
        $dataSites = [];
        define("SUBDOMAIN_PREFIX", "sc-domain:");

        //Construct date range
        $date = new DateRange(); 
        $date->setStartDate($request->input('start_date'));
        $date->setEndDate($request->input('end_date'));

        //Get sites domains to get analytics
        $sites = collect(config('apis.search-console.domains', []))
            ->forPage($this->page, $this->perPage);

        foreach($sites as $site){
            $dataSites[str_replace(SUBDOMAIN_PREFIX, "", $site)] = $service->getAnalytics($site, $date)->getRows();
        }

        return new SearchAnalyticCollection($dataSites);
    }

    /**
     * @OA\Get(
     *     path="/api/analytics",
     *     summary="Consume Google Analytics API and return data",
     *     tags={"Analytics"},
     *     @OA\QueryParameter(
     *        name="start_date",
     *        required=true,
     *        description="Start date to get analytics"
     *     ),
     *     @OA\QueryParameter(
     *        name="end_date",
     *        required=true,
     *        description="End date to get analytics"
     *     ),
     *     @OA\QueryParameter(
     *        name="page",
     *        required=false,
     *        description="Page",
     *        example=1
     *     ),
     *     @OA\QueryParameter(
     *        name="per_page",
     *        required=false,
     *        description="Quantity of items per page",
     *        example=3
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/google-ads",
     *     summary="Consume Google Ads API and return data",
     *     tags={"Analytics"},
     *     @OA\QueryParameter(
     *        name="start_date",
     *        required=true,
     *        description="Start date to get analytics"
     *     ),
     *     @OA\QueryParameter(
     *        name="end_date",
     *        required=true,
     *        description="End date to get analytics"
     *     ),
     *     @OA\QueryParameter(
     *        name="page",
     *        required=false,
     *        description="Page",
     *        example=1
     *     ),
     *     @OA\QueryParameter(
     *        name="per_page",
     *        required=false,
     *        description="Quantity of items per page",
     *        example=3
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function googleAds(GoogleAdsService $service, Request $request) 
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $customer_ids = collect(config('apis.google-ads.customer_ids', []))
            ->forPage($this->page, $this->perPage);

        $analytics = [];

        foreach($customer_ids as $customer_id){
            $analytic = $service->getAnalytics($customer_id, $start_date, $end_date);
            array_push($analytics, (object) $analytic);
        }

        //Delete repeated data
        $analytics = collect($analytics)->collapse()->unique('adGroup');

        return GoogleAdsResource::collection($analytics);
    }

    /**
     * @OA\Get(
     *     path="/api/wp",
     *     summary="Consume WordPress API and return data",
     *     tags={"Analytics"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function wp(WpOkboxService $service) 
    {
        $data = $service->getStats();

        //Check if there is an error in the response
        if(isset($data['data']) && $data['data']['status'] >= 400 && $data['data']['status'] < 500){
            throw new InvalidResourceException("Unknown error getting wordpress data");
        }
            
        return new WpResource((object) $data);
    }

    /**
     * @OA\Get(
     *     path="/api/sp-manager",
     *     summary="Consume WordPress API and return data",
     *     tags={"Analytics"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function spManager(SpaceManagerService $spaceManagerService)
    {
        $data = $spaceManagerService->getStats();

        return new SpManagerResource((object) $data);
    }
}
