<?php


$command_options = [
    (object) [
        'long' => 'username',
        'short' => 'u',
        'type' => 'param',
    ],
    (object) [
        'long' => 'password',
        'short' => 'p',
        'type' => 'param',
    ],
    (object) [
        'long' => 'token',
        'short' => 't',
        'type' => 'param',
    ],
    (object) [
        'long' => 'non-interactive',
        'short' => 'I',
        'type' => 'flag',
        'negates' => 'interactive',
    ],
    (object) [
        'long' => 'interactive',
        'short' => 'i',
        'type' => 'flag',
    ],
];

$command = array_shift($argv);
$options = [];
$newargv = [];

foreach ($argv as $i => $a) {
    if (preg_match('/^-([^-].*)/', $argv[$i], $groups)) {
        for ($c = 0; $c < strlen($groups[1]); $c++) {
            $char = substr($groups[1], $c, 1);
            $newargv[] = '-' . $char;
        }
    } else {
        $newargv[] = $a;
    }
}

$argv =& $newargv;
unset($newargv);

$residual_args = [];

for ($i = 0; $i < count($argv); $i++) {
    $isOption = false;

    foreach ($command_options as $option) {
        if (in_array($argv[$i], ['--' . $option->long, '-' . $option->short])) {
            if ($option->type == 'param') {
                $i++;

                if (!isset($argv[$i])) {
                    usage();
                    die();
                }

                $options[$option->long] = $argv[$i];
            } elseif ($option->type == 'flag') {
                if (@$option->negates) {
                    $options[$option->negates] = false;
                } else {
                    $options[$option->long] = true;
                }
            }

            $isOption = true;
        } elseif (preg_match('/^--' . $option->long . '=(.*)/', $argv[$i], $groups)) {
            $options[$option->long] = $groups[1];
            $isOption = true;
        }
    }

    if (!$isOption) {
        $residual_args[] = $argv[$i];
    }
}

foreach ($residual_args as $arg) {
    if (preg_match('/^-/', $arg)) {
        error_log('Unrecognised option: ' . $arg);
        usage();
        die();
    }
}

$argv = array_merge([$command], $residual_args);
$jars = new Jars();

if ($token) {
    define('AUTH_TOKEN', $token);
} elseif ($username) {
    define('USERNAME', $username);

    if (!$password) {
        echo "Password: ";
        $password = read_password();
    }

    define('PASSWORD', $password);
}

unset($username);
unset($password);
unset($token);

require WWW_HOME . '/plugins/subsimple/subsimple.php';

function read_password()
{
    $f = popen("/bin/bash -c 'read -s'; echo \$REPLY", "r");
    $input = fgets($f, 100);
    pclose($f);
    echo "\n";

    return $input;
}


function usage()
{
    die("Usage: \n");
}