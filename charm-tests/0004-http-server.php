<?php
require(__DIR__.'/../vendor/autoload.php');

use Moebius\Coroutine as Co;

$http = new React\Http\HttpServer(function (Psr\Http\Message\ServerRequestInterface $request) {
    return React\Http\Message\Response::plaintext(
        Co::await(slow_text(...))
    );
});

$socket = new React\Socket\SocketServer('0.0.0.0:8080');
$http->listen($socket);


function slow_text() {
    Co::sleep(1);
    return "This text took 1 second to generate";
}
