<?php

namespace App\Services\GoogleServices;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClientBuilder;

class GoogleAdsService extends GoogleService {
    function getAnalytics($customer_id = null) {
        if($customer_id == null) return;

        //Create oauth settings
        $oAuth2Credential = (new OAuth2TokenBuilder())
            ->withScopes("https://www.googleapis.com/auth/adwords")
            ->withJsonKeyFilePath(env("GOOGLE_APPLICATION_CREDENTIALS", ""))
            ->withImpersonatedEmail("google-search-consumer@okbox-380719.iam.gserviceaccount.com")
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
                . "campaign.name, "
                . "ad_group.id, "
                . "ad_group.name, "
                . "ad_group_criterion.criterion_id, "
                . "ad_group_criterion.keyword.text, "
                . "ad_group_criterion.keyword.match_type, "
                . "metrics.impressions, "
                . "metrics.clicks, "
                . "metrics.cost_micros "
            . "FROM keyword_view "
            . "WHERE segments.date DURING LAST_7_DAYS "
                . "AND campaign.advertising_channel_type = 'SEARCH' "
                . "AND ad_group.status = 'ENABLED' "
                . "AND ad_group_criterion.status IN ('ENABLED', 'PAUSED') "
            // Limits to the 50 keywords with the most impressions in the date range.
            . "ORDER BY metrics.impressions DESC "
            . "LIMIT 50";

        //Execute query
        $res = $googleAdsServiceClient->search($customer_id, $query);
        dd($res);
        return null;
    }
} 