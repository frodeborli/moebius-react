<?php
use React\EventLoop\Loop as ReactLoop;
use Moebius\Coroutine as Co;
/**
 * When we create the event loop this way, react will not register
 * shutdown functions to start.
 */
/*
$loop = React\EventLoop\Factory::create();
Co::go(function() use ($loop) {
    while (true) {
        $loop->futureTick(function() use ($loop) {
            $loop->stop();
        });
        $loop->run();
        Co::suspend();
    }
});
*/
echo "setting loop\n";
ReactLoop::set(new Moebius\React\MoebiusEventLoop());

/*
Co::go(function()
Moebius
React\EventLoop\Loop::set(new Moebius\React\MoebiusEventLoop());
*/
