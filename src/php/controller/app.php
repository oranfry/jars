<?php

use jars\contract\JarsConnector;
use subsimple\Config;
use subsimple\Exception;

switch (preg_replace(',.*/,', '', $_plugin_dir)) {
    case 'jars-cli':
        global $jars;

        break;

    case 'jars-admin':
        $jars = JarsConnector::connect(Config::get()->connection_string);

        $token = null;

        switch (AUTHSCHEME) {
            case 'header':
                $token = @getallheaders()['X-Auth'];
                break;

            case 'cookie':
                $token = @$_COOKIE['token'];
                break;

            case 'none':
                break;

            default:
                throw new Exception('Unsupported AUTHSCHEME');
        }

        if (in_array(AUTHSCHEME, ['header', 'cookie'])) {
            if (!$token) {
                header('Location: ' . JARS_ADMIN_HOMEPATH);
                die();
            }

            $jars->token($token);

            if (!$jars->touch()) {
                setcookie('token', '', time() - 3600);
                header('Location: ' . JARS_ADMIN_HOMEPATH);
                die();
            }
        }

        break;

    case 'jars-http':
        $jars = JarsConnector::connect(Config::get()->connection_string);

        switch (AUTHSCHEME) {
            case 'header':
                $jars
                    ->token(@getallheaders()['X-Auth'])
                    ->touch();

                break;

            case 'none':
                break;

            default:
                throw new Exception('Unsupported AUTHSCHEME');
        }

        break;

    default:
        throw new Exception('Unsupported Plugin');
}

return compact('jars');
