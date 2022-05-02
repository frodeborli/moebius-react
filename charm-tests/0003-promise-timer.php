<?php
require(__DIR__.'/../vendor/autoload.php');

use Moebius\Coroutine as Co;

echo "Waiting for react promise timer of 2 seconds: ";
$promise = new Moebius\Promise();
try {
    Co::await(React\Promise\Timer\timeout($promise, 2.0));
} catch (\Throwable $e) {
    echo "Exception okay!\n";
}

