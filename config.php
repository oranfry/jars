<?php

const JARS_ADMIN_HOME = APP_HOME . '/vendor/oranfry/jars-admin';
const JARS_CLI_HOME = APP_HOME . '/vendor/oranfry/jars-cli';
const JARS_CLIENT_HOME = APP_HOME . '/vendor/oranfry/jars-client';
const JARS_CORE_HOME = APP_HOME . '/vendor/oranfry/jars-core';
const JARS_HTTP_HOME = APP_HOME . '/vendor/oranfry/jars-http';
const SUBSIMPLE_HOME = APP_HOME . '/vendor/oranfry/subsimple';

return (object) [
    'jars' => @$jars_config,
    'requires' => [
        JARS_ADMIN_HOME,
        JARS_CLI_HOME,
        JARS_CLIENT_HOME,
        JARS_CORE_HOME,
        JARS_HTTP_HOME,
        SUBSIMPLE_HOME,
    ],
    'router' => 'jars\JarsRouter',
];
