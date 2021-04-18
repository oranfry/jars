<?php
namespace jars;
/**
 * Assists in building your jars CLI
 */
class JarsCliHelper
{
    public $token;
    public $username;
    public $password;
    public $interactive;
    public $onetime;
    public $persistent;

    static $command_options;

    public function __construct($argv)
    {
        $command = array_shift($argv);
        $options = [];

        for ($i = 0; $i < count($argv); $i++) {
            if (preg_match('/^-([^-].*)/', $argv[$i], $groups)) {
                $expanded = [];

                for ($c = 0; $c < strlen($groups[1]); $c++) {
                    $char = substr($groups[1], $c, 1);
                    $expanded[] = '-' . $char;
                }

                array_splice($argv, $i, 1, $expanded);
            }
        }

        $residual = [];

        for ($i = 0; $i < count($argv); $i++) {
            $isOption = false;

            foreach (static::$command_options as $option) {
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
                $residual[] = $argv[$i];
            }
        }

        foreach ($residual as $arg) {
            if (preg_match('/^-/', $arg)) {
                error_log('Unrecognised option: ' . $arg);
                static::usage();
                die();
            }
        }

        $argv = array_merge([$command], $residual_args);
    }

    public static function __static()
    {
        self::$command_options = [
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
            (object) [
                'long' => 'onetime',
                'short' => 'o',
                'type' => 'flag',
            ],
            (object) [
                'long' => 'persistent',
                'short' => 'O',
                'negates' => 'interactive',
            ],
        ];
    }

    static function usage()
    {
        die("Usage: \n");
    }
}

JarsCliHelper::__static();
