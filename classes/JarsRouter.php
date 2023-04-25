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

        'HTTP /api.*' => [
            'FORWARD' => HttpRouter::class,
            'EAT' => '/api',
        ],

        'HTTP .*' => [
            'FORWARD' => AdminRouter::class,
        ],
   ];
}
