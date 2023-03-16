<?php

namespace App\Services\GoogleServices;

use Illuminate\Support\Facades\Config;
use Google\Service\SearchConsole;
use Google\Service\SearchConsole\SearchAnalyticsQueryRequest;
use Google\Service\AnalyticsReporting\DateRange;

class GoogleSearchConsoleService extends GoogleService{
    function getAnalytics(string $url, DateRange $date){
        parent::createClient();
        $this->client->addScope(SearchConsole::WEBMASTERS);
        $googleSearch = new SearchConsole($this->client);
        $query = $this->createQuery($date);

        return $googleSearch->searchanalytics->query($url, $query);
    }

    function createQuery(DateRange $date): SearchAnalyticsQueryRequest {
        $query = new SearchAnalyticsQueryRequest();
        $query->setStartDate($date->getStartDate());
        $query->setEndDate($date->getEndDate());
        $query->setDimensions(["query"]);

        return $query;
    }
}