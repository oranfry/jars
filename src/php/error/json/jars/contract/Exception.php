<?php

\Sentry\captureException($exception);

$public_message ??= $exception->getMessage() ?? null;

return false;
