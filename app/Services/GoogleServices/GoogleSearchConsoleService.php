<?php

namespace App\Services\GoogleServices;

use Illuminate\Support\Facades\Config;
use Google\Service\SearchConsole;

class GoogleSearchConsoleService extends GoogleService{
    function __construct(){
        parent::__construct(Config::get('services.google.credentials_path'));
    }

    function getSiteMaps(string $url){
        parent::createClient();
        $this->client->addScope(SearchConsole::WEBMASTERS_READONLY);
        $googleSearch = new SearchConsole($this->client);

        return $googleSearch->sitemaps->listSitemaps($url);
    }
}