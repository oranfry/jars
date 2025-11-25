<?php

define('APP_HOME', dirname(__DIR__));

require APP_HOME . '/vendor/autoload.php';

// first match a portal, run its autoload (if any), and get its connection string

$connection_string = (function () {
    $throwError = function ($code, $message = null): never {
        if ($message) {
            error_log('Jars Admin: ' . $message);
        }

        http_response_code($code);
        die('<body style="padding: 0; margin:0; background: #eee"><h1 style="font-family: sans-serif;line-height: 100vh; text-align: center; font-size: 25vh; color: #bbb">' . $code . '</h1></body>');
    };

    $etc = defined('ETC_DIR') ? ETC_DIR : '/etc/jars';
    $config_file = null;
    $host_config = (object) [];

    if (!defined('MATCH_MODE')) {
        define('MATCH_MODE', isset($_SERVER['MATCH_MODE']) ? $_SERVER['MATCH_MODE'] : 'domain');
    }

    switch (MATCH_MODE) {
        case 'domain':
            if (isset($_SERVER['HTTP_HOST'])) {
                foreach([
                    APP_HOME . '/configs/' . $_SERVER['HTTP_HOST'] . '.php',
                    APP_HOME . '/public/configs/' . $_SERVER['HTTP_HOST'] . '.php',
                ] as $f) {
                    if (file_exists($f)) {
                        $config_file = $f;
                        break;
                    }
                }
            }

        case 'none':
            define('JARS_ADMIN_BASEPATH', '');
            define('JARS_ADMIN_HOMEPATH', '/');
            break;

        case 'path':
            if (
                !isset($_SERVER['REQUEST_URI'])
                || !preg_match(',^/([a-z]+),', $_SERVER['REQUEST_URI'], $matches)
            ) {
                $throwError(404);
            }

            define('MATCHED_PATH', $matches[1]);
            define('JARS_ADMIN_BASEPATH', '/' . MATCHED_PATH);
            define('JARS_ADMIN_HOMEPATH', JARS_ADMIN_BASEPATH);

            foreach([
                APP_HOME . '/configs/' . MATCHED_PATH . '.php',
                APP_HOME . '/public/configs/' . MATCHED_PATH . '.php',
            ] as $f) {
                if (file_exists($f)) {
                    $config_file = $f;
                    break;
                }
            }

            // config file is mandatory in path mode (use empty file if needed to signify existence)

            if (!$config_file) {
                $throwError(404);
            }

            break;

        default:
            $throwError(500, 'Invalid MATCH_MODE. Should be "domain", "path", or "none"');
    }

    if ($config_file && file_exists($config_file)) {
        $result = require $config_file;

        if (is_object($result)) {
            $host_config = $result;
        } elseif ($result !== 1 && $result !== null) {
            $throwError(500, 'Host config file should return an object, null, 1, or not return at all.');
        }
    }

    // load the centralised portal settings if appropriate

    $portal = match(true) {
        (bool) ($_SERVER['PORTAL'] ?? null) => $_SERVER['PORTAL'],
        property_exists($host_config, 'portal') => $host_config->portal,
        defined('MATCHED_PATH') => MATCHED_PATH,
        default => null,
    };

    if (
        $portal
        && file_exists($config_file = $etc . '/' . $portal . '.json')
        && ($portal_config = json_decode(file_get_contents($config_file)))
    ) {
        $host_config = (object) ((array) $host_config + (array) $portal_config);
    }

    if (!$connection_string = $host_config->connection_string ?? $_SERVER['CONNECTION_STRING'] ?? null) {
        $throwError(500, 'Please define connection string in host config or as environment variable');
    }

    if ($autoload = $host_config->autoload ?? $_SERVER['PORTAL_AUTOLOAD'] ?? null) {
        require $autoload;
    }

    return $connection_string;
})();

// now boot subsimple

require APP_HOME . '/vendor/oranfry/subsimple/subsimple.php';
