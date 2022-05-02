<?php
use React\EventLoop\Loop as ReactLoop;
use Moebius\Coroutine as Co;
/**
 * Set the React event loop implementation to ours.
 */

ReactLoop::set(new Moebius\React\MoebiusEventLoop());
/*
Co::go(function()
Moebius
React\EventLoop\Loop::set(new Moebius\React\MoebiusEventLoop());
*/
