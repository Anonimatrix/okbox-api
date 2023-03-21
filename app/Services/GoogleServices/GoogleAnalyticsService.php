<?php

namespace App\Services\GoogleServices;

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\RunReportResponse;
use Google\Protobuf\Internal\RepeatedField;

class GoogleAnalyticsService {
    function getAnalytics(string $propertyId, DateRange $dateRange): array {
        $client = new BetaAnalyticsDataClient();

        /**
         * @var string[] $dimensions array of names dimensions
         */
        $dimensions = config('apis.analytics.dimensions', []);
        /**
         * @var string[] $metrics array of names metrics
         */
        $metrics = config('apis.analytics.metrics', []);

        // Make an API call.
        $response = $client->runReport([
            'property' => 'properties/' . $propertyId,
            'dateRanges' => [ $dateRange ],
            'dimensions' => $this->createGoogleMessage($dimensions, Dimension::class),
            'metrics' => $this->createGoogleMessage($metrics, Metric::class)
            
        ]);

        return $this->parseAnalytics($response);
    }

    /** 
     * Format data (Dimensions or metrics) to Google Analytics format
     * @param string[] $data array of strings with parameters
     * @param string $class class name of Google Analytics object
    */
    function createGoogleMessage(array $data, $class): array {
        $arr = [];
        foreach($data as $item){
            $arr[] = new $class(
                [
                    'name' => $item,
                ]
            );
        }

        return $arr;
    }

    /**
     * Parse response data from Google Analytics
     * @param RunReportResponse $response 
     * @return array Return array with metrics and dimensions
     */
    function parseAnalytics(RunReportResponse $response): array {
        $data = [];
        $dimensionsHeaders = $response->getDimensionHeaders();
        $metricsHeaders = $response->getMetricHeaders();
        $rows = $response->getRows();

        for($i = 0; $i < count($rows); $i++){
            $row = $rows->offsetGet($i);
            array_push($data, [
                'metrics' => $this->formatRowData($metricsHeaders, $row->getMetricValues()),
                'dimensions' => $this->formatRowData($dimensionsHeaders, $row->getDimensionValues()),
            ]);
        }

        return $data;
    }

    /**
     * Get metrics or dimensions from response data
     * @param RepeatedField $headers
     * @param RepeatedField $values
     * @return array Return array with formatted headers and values of metrics or dimensions 
     */
    function formatRowData(RepeatedField $headers, RepeatedField $values) {
        $data = [];
        for($i = 0; $i < count($values); $i++){
            $data[$headers->offsetGet($i)->getName()] = $values->offsetGet($i)->getValue();
        }

        return $data;
    }
}