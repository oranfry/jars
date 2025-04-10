<?php

namespace jars;

use jars\admin\AdminRouter;
use jars\cli\CliRouter;
use jars\http\HttpRouter;

class JarsRouter extends \subsimple\Router
{
    protected static $routes = [
        'CLI *' => [
            'FORWARD' => CliRouter::class,
        ],
   ];
}

(function () {
    $prefix = !defined('MATCH_MODE') || MATCH_MODE !== 'path' || !defined('MATCHED_PATH') ? null : '/' . MATCHED_PATH;

    JarsRouter::add("HTTP $prefix/api.*", [
        'FORWARD' => HttpRouter::class,
        'EAT' => $prefix . '/api',
    ]);

    JarsRouter::add("HTTP $prefix.*", [
        'FORWARD' => AdminRouter::class,
        'EAT' => $prefix,
    ]);
})();
