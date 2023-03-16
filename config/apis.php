<?php 

return [
    "search-console" => [
        'domains' => [ // Domains are the websites you want to get search console data from
            "https://okbox.fr/"
        ],
        "dimensions" => [
            "query",
            "page",
            "device",
            "country"
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

    ]
];