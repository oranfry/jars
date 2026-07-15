<?php

if (defined('SENTRY_DSN')) {
    if (
        defined('JARS_LOG_404S')
        && JARS_LOG_404S
        || PHP_SAPI !== 'cli'
        && !$exception instanceof \OranFry\Subsimple\NotFoundException
    ) {
        \Sentry\captureException($exception);
    }
}

return false;
