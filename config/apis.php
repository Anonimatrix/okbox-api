<?php 

// In this file you can define configs of APIs you want to use

return [
    "search-console" => [
        'domains' => [ // Domains are the websites you want to get search console data from
            "https://okbox.fr/"
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
            "316178610"
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
        "customer_ids" => ["2512373823"],
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