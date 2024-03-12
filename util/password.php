<?php

$salt = implode("", array_map(fn () => chr(random_int(32, 126)), array_fill(0, 64, 0)));

if (!($argv[1] ?? false)) {
    echo "Please provide username as first argument\n";
    error_log('Please provide username as first argument');
    die(1);
}

if (!($argv[2] ?? false)) {
    echo "Please provide password as first argument\n";
    error_log('Please provide password as first argument');
    die(1);
}

echo "    public function credentialsCorrect(string \$username, string \$password): bool\n";
echo "    {\n";
echo "        if (\$username !== '" . escsquot($argv[1]) . "') {\n";
echo "            return false;\n";
echo "        }\n\n";
echo "        return hash('sha256', '" . escsquot($salt) . "' . \$password)\n            === '" . hash('sha256', $salt . $argv[2]) . "';\n";
echo "    }\n";

function escsquot(string $string): string
{
    return str_replace(['\\', "'"], ['\\\\', "\\'",], $string);
}
