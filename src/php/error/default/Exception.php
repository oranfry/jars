<?php

if (defined('SENTRY_DSN')) {
    \Sentry\captureException($exception);
}

return false;
