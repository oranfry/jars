<?php

if (defined('SENTRY_DSN')) {
    if (
        defined('JARS_LOG_404S')
        && JARS_LOG_404S
        || SUBSIMPLE_METHOD !== 'CLI'
        && !$exception instanceof \subsimple\NotFoundException
    ) {
        \Sentry\captureException($exception);
    }
}

return false;
