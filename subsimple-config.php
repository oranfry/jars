<?php

return (object) [
    'connection_string' => @$connection_string,
    'requires' => [
        APP_HOME . '/vendor/oranfry/jars-admin',
        APP_HOME . '/vendor/oranfry/jars-cli',
        APP_HOME . '/vendor/oranfry/jars-http',
        APP_HOME . '/vendor/oranfry/subsimple',
    ],
    'router' => 'jars\JarsRouter',
];
