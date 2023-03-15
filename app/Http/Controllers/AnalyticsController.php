<?php

namespace App\Http\Controllers;

use App\Services\GoogleServices\GoogleSearchConsoleService;
use Illuminate\Support\Facades\Config;

class AnalyticsController extends Controller
{
    public function searchConsole(GoogleSearchConsoleService $service) 
    {
        $dataSites = [];

        $sites = Config::get('sites.sites');

        foreach($sites as $site){
            array_push($dataSites, $service->getSiteMaps($site));
        }

        return response()->json(
            [
                "data" => $dataSites
            ]
        );
    }
}
