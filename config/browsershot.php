<?php

return [
    'nodeBinary' => env('BROWSERSHOT_NODE_BINARY', '/usr/bin/node'),
    'npmBinary'  => env('BROWSERSHOT_NPM_BINARY', '/usr/bin/npm'),
    'chromePath' => env('BROWSERSHOT_CHROME_PATH', '/usr/bin/google-chrome'),
];
