<?php

namespace jars;

class JarsRouter extends \subsimple\Router
{
    protected static $routes = [
        'CLI *' => [
            'FORWARD' => '\jars\cli\CliRouter',
        ],

        'HTTP /api.*' => [
            'FORWARD' => '\jars\http\HttpRouter',
            'EAT' => '/api',
        ],

        'HTTP .*' => [
            'FORWARD' => '\jars\admin\AdminRouter',
        ],
   ];
}
