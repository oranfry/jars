<?php

if (defined('SENTRY_DSN')) {
    \Sentry\init([
        'dsn' => SENTRY_DSN,
        'traces_sample_rate' => 1.0,
        'profiles_sample_rate' => 1.0,
    ]);
}
