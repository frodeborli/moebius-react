<?php
require(__DIR__.'/../vendor/autoload.php');

require(__DIR__.'/../Loop.php');

$process = new React\ChildProcess\Process('echo foo');
$process->start();

$process->stdout->on('data', function ($chunk) {
    echo $chunk;
});

$process->on('exit', function($exitCode, $termSignal) {
    echo 'Process exited with code ' . $exitCode . PHP_EOL;
});

