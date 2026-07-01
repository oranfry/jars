<?php

namespace OranFry\Jars\Bundle;

use OranFry\Jars\Admin\AdminRouter;
use OranFry\Jars\CLI\CliRouter;
use OranFry\Jars\HTTP\HttpRouter;

class Router extends \subsimple\Router
{
    protected static $routes = [
        'CLI *' => [
            'FORWARD' => CliRouter::class,
        ],
   ];
}

(function () {
    $prefix = !defined('MATCH_MODE') || MATCH_MODE !== 'path' || !defined('MATCHED_PATH') ? null : '/' . MATCHED_PATH;

    Router::add("HTTP $prefix/api.*", [
        'FORWARD' => HttpRouter::class,
        'EAT' => $prefix . '/api',
    ]);

    Router::add("HTTP $prefix.*", [
        'FORWARD' => AdminRouter::class,
        'EAT' => $prefix,
    ]);
})();
