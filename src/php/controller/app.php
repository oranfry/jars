<?php

if (!file_exists($plugin_controller_file = $_plugin_dir . '/src/php/controller/plugin.php')) {
    error_response('Could not locate plugin controller');
}

return require $plugin_controller_file;

