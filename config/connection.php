<?php

return [
    "service" => [
        "service-fazz" => [
            "protocol" => "https",
            "endpoint" => (env('APP_ENV') === 'production') ? env('FAZZ_URL') : env('FAZZ_URL_DEV')
        ]
    ]
];
