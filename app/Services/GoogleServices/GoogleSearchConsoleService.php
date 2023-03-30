<?php

namespace App\Services\GoogleServices;

use Google\Service\SearchConsole;
use Google\Service\SearchConsole\SearchAnalyticsQueryRequest;
use Google\Service\AnalyticsReporting\DateRange;

class GoogleSearchConsoleService extends GoogleService {
    /**
     * Get analytics from Google Search Console API of url with date range and config dimensions
     * @param string $url Url to get analytics
     * @param DateRange $date Date range to get analytics
     */
    function getAnalytics(string $url, DateRange $date){
        parent::createClient();
        $this->client->addScope(SearchConsole::WEBMASTERS);
        $googleSearch = new SearchConsole($this->client);
        $query = $this->createQuery($date);

        return $googleSearch->searchanalytics->query($url, $query);
    }

    /** 
     * Create query with range date and dimensions for Google Search Console API
     * @param DateRange $date Date range to get analytics
    */
    function createQuery(DateRange $date): SearchAnalyticsQueryRequest {
        $dimensions = config('apis.search-console.dimensions', []);
        $query = new SearchAnalyticsQueryRequest();
        $query->setStartDate($date->getStartDate());
        $query->setRowLimit(config('apis.search-console.row-limit', 50));
        $query->setEndDate($date->getEndDate());
        $query->setDimensions($dimensions);

        return $query;
    }
}