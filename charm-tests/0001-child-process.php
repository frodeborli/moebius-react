<?php
require(__DIR__.'/../vendor/autoload.php');

$process = new React\ChildProcess\Process('echo Successfully echoed');
$process->start();

$process->stdout->on('data', function ($chunk) {
    echo "STDOUT: '".json_encode($chunk)."'\n";
});

$process->stderr->on('data', function ($chunk) {
    echo "STDERR: '".json_encode($chunk)."'\n";
});

$process->on('exit', function($exitCode, $termSignal) {
    echo 'Process exited on signal ' . $termSignal . ' with code ' . $exitCode . PHP_EOL;
});

