<?php

namespace App\Services\GoogleServices;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClientBuilder;

class GoogleAdsService {
    function getAnalytics($customer_id = null, $start_date = null, $end_date = null) {
        if($customer_id == null) return;

        //Create oauth settings
        $oAuth2Credential = (new OAuth2TokenBuilder())
            ->withScopes("https://www.googleapis.com/auth/adwords")
            ->withJsonKeyFilePath(env("GOOGLE_APPLICATION_CREDENTIALS", ""))
            ->withImpersonatedEmail("jfs@ndodigital.com")
            ->build();

        // Set developer key
        $developerKey = env("GOOGLE_ADS_DEVELOPER_KEY", "");

        //Create Google ads client
        $builder = new GoogleAdsClientBuilder();
        $googleAdsClient = $builder
            ->withOAuth2Credential($oAuth2Credential)
            ->withDeveloperToken($developerKey)
            ->build();
        
        //Create Google Ads Service
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();

        //Create query
        $query =
            "SELECT campaign.id, "
                . "ad_group.id, "
                . "ad_group.name, "
                . "metrics.impressions, "
                . "metrics.clicks, "
                . "metrics.average_cpc, "
                . "metrics.ctr "
            . "FROM ad_group "
            . "WHERE ad_group.status = 'ENABLED' "
            . ($start_date ? "AND segments.date > '{$start_date}' " : "")
            . ($end_date ? "AND segments.date < '{$end_date}' " : "")
            // Limits to the 50 keywords with the most impressions in the date range.
            . "ORDER BY metrics.impressions DESC "
            . "LIMIT 50";

        //Execute query
        $res = $googleAdsServiceClient->search($customer_id, $query);

        $metrics = collect($res->getPage()->getResponseObject()->getResults())
            ->map(function($item, $key){
                $adGroup = $item->getAdGroup()->getName();
                $metrics = $item->getMetrics();
                return compact('adGroup', 'metrics');
            });

        return $metrics;
    }
} 