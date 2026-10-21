<?php

return [
    "btc" => [
        "node_url" => env('BTC_NODE_URL', '')
    ],
    'btc_config_updater' => explode(',', env('BTC_CONFIG_UPDATER', '')),
];