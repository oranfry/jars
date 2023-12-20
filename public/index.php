<?php

define('APP_HOME', dirname(__DIR__));

@include APP_HOME . '/machine.php';

$host_config = (object) [];

if (isset($_SERVER['HTTP_HOST']) && file_exists($config_file = __DIR__ . '/configs/' . $_SERVER['HTTP_HOST'] . '.php')) {
    $host_config = require $config_file;

    if (!is_object($host_config)) {
        error_log('Jars Admin: Host config file should return an object.');
        die(1);
    }
}

if (!$connection_string = $host_config->connection_string ?? $_SERVER['CONNECTION_STRING'] ?? null) {
    error_log('Please define connection string in host config or as environment variable');
    die(1);
}

if ($autoload = $host_config->autoload ?? $_SERVER['PORTAL_AUTOLOAD'] ?? null) {
    require $autoload;
}

require APP_HOME . '/vendor/autoload.php';
require APP_HOME . '/vendor/oranfry/subsimple/subsimple.php';
