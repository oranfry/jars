<?php

use jars\client\HttpClient;
use jars\Jars;
use subsimple\Config;

switch (preg_replace(',.*/,', '', $_plugin_dir)) {
    case 'jars-cli':
        $jars = Jars::of(PORTAL_HOME, DB_HOME);

        if (defined('AUTH_TOKEN')) {
            $jars->token(AUTH_TOKEN);
        } else {
            $jars->login(USERNAME, PASSWORD, true);
        }

        break;

    case 'jars-admin':
        $jars_config = Config::get()->jars;

        if (@$jars_config->portal_home) {
            $jars = Jars::of($jars_config->portal_home, $jars_config->db_home);
        } else {
            $jars = HttpClient::of($jars_config->jars_url);
        }

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
                error_response('Internal Server Error', 500);
        }

        if (in_array(AUTHSCHEME, ['header', 'cookie'])) {
            if (!$token) {
                header('Location: /');
                die();
            }

            $jars->token($token);

            if (!$jars->touch()) {
                setcookie('token', '', time() - 3600);
                header('Location: /');
                die();
            }
        }

        break;

    case 'jars-http':
        $jars_config = Config::get()->jars;
        $jars = Jars::of($jars_config->portal_home, $jars_config->db_home);

        switch (AUTHSCHEME) {
            case 'header':
                if (!$token = @getallheaders()['X-Auth']) {
                    error_response('Invalid auth ' . PAGE);
                }

                $jars->token($token);

                if (!$jars->touch()) {
                    error_response('Invalid auth');
                }

                break;

            case 'none':
                break;

            default:
                error_response('Internal Server Error', 500);
        }

        break;

        default:
            error_response('Internal Server Error', 500);
}

return compact('jars');
