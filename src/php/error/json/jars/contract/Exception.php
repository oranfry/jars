<?php

if (defined('SENTRY_DSN')) {
    \Sentry\captureException($exception);
}

$public_message ??= $exception->getMessage() ?? null;

return false;
