<?php 

// In this file you can define configs of APIs you want to use

$domains = explode(",", env('SEARCH_CONSOLE_DOMAINS', "")); // You can add more domains in env file separated by comma
$properties = explode(",", env('ANALYTICS_PROPERTIES', "")); // You can add more properties in env file separated by comma
$customer_ids = explode(",", env('GOOGLE_ADS_CUSTOMER_IDS', "")); // You can add more ids in env file separated by comma

function isVoid(array $values) {
    return (count($values) > 0 && $values[0] !== "");
}

return [
    "search-console" => [
        'domains' => [ // Domains or domains properties are the websites you want to get search console data from
            "https://okbox.fr/",
            "sc-domain:okbox.fr",
            "sc-domain:caen.okbox.fr",
            "sc-domain:alencon.okbox.fr",
            ...(isVoid($domains) ? ($domains) : []) //Adding domains if there are some in env file
        ],
        "dimensions" => [
            "query",
            // "page",
            // "device",
            // "country"
        ]
    ],
    "analytics" => [
        "properties" => [ // Properties are the websites you want to get analytics from
            "316178610",
            "316165926",
            "316160496",
            "316174891",
            "316167605",
            "316102470",
            "316182646",
            "316169810",
            "316118011",
            ...(isVoid($properties) ? ($properties) : []) //Adding properties if there are some in env file
        ],
        "dimensions" => [
            "defaultChannelGroup"
        ],
        "metrics" => [
            // 'active28DayUsers', 
            // 'addToCarts', 
            // 'cartToViewRate', 
            // 'checkouts',
            // "screenPageViews",
            "totalUsers",
            "sessions"
            // "promotionClicks"
        ]
        ],
    "google-ads" => [
        "customer_ids" => [
            "2512373823",
            ...(isVoid($customer_ids) ? ($customer_ids) : []) // Adding ids if there are some in env file
        ],
    ],
    "wordpress" => [
        "base_url" => "https://okbox.fr/wp-json/log-notifications/v1/",
        "dev_base_url" => "https://ndo.agency/wp-json/log-notifications/v1/"
    ],
    "space-manager" => [
        "base_url" => "https://185.63.49.14:8081/spaceman/",
        "dev_base_url" => "https://185.63.49.14:8081/spaceman/" //https://185.63.49.14:8082/webdemo/
    ]
];