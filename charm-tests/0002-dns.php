<?php
require(__DIR__.'/../vendor/autoload.php');

use Moebius\Coroutine as Co;

$config = React\Dns\Config\Config::loadSystemConfigBlocking();

if (!$config->nameservers) {
    $config->nameservers[] = '8.8.8.8';
}

$factory = new React\Dns\Resolver\Factory();
$dns = $factory->create($config);

echo "Resolving using await: ".Co::await($dns->resolve('ennerd.com'))."\n";
echo "Waiting for idle timer to expire\n";
