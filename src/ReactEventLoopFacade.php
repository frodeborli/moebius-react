<?php
namespace Moebius\React;

use React\EventLoop\LoopInterface;
use React\EventLoop\TimerInterface;
use WeakReference;
use WeakMap;
use Moebius\Loop;

class ReactEventLoopFacade implements LoopInterface {
    private array $readListeners = [];
    private array $writeListeners = [];
    private array $signalListeners = [];
    private WeakMap $storage;
    private bool $stopCalled = false;
    private bool $debug = false;

    public function __construct() {
        if (getenv('DEBUG')) {
            $this->debug = true;
        }
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        $this->storage = new WeakMap();
    }

    public function run() {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        Loop::drain(function() {
            if ($this->stopCalled) {
                $this->stopCalled = false;
                return true;
            }
            return false;
        });
    }

    public function stop() {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        if (Loop::isDraining()) {
            $this->stopCalled = true;
        }
    }

    public function addReadStream($stream, $listener) {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        $streamId = get_resource_id($stream);

        if (isset($this->readListeners[$streamId])) {
            throw new \Exception("Already have a read listener for this stream. The react/event-loop only supports one read listener per stream.");
        }

        $this->readListeners[$streamId] = [ $stream, $listener, Loop::onReadable($stream, $listener) ];
    }

    public function addWriteStream($stream, $listener) {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        $streamId = get_resource_id($stream);
        if (isset($this->writeListeners[$streamId]) && $this->writeListeners[$streamId]->get() !== null) {
            throw new \Exception("Already have a write listener for this stream. The react/event-loop only supports one write listener per stream.");
        }
        $this->writeListeners[$streamId] = [ $stream, $listener, Loop::onWritable($stream, $listener) ];
    }

    public function removeReadStream($stream) {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        $streamId = get_resource_id($stream);

        if (isset($this->readListeners[$streamId])) {
            $cancelFunction = $this->readListeners[$streamId][2];
            unset($this->readListeners[$streamId]);
            $cancelFunction();
        }
    }

    public function removeWriteStream($stream) {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        $streamId = get_resource_id($stream);

        if (isset($this->writeListeners[$streamId])) {
            $cancelFunction = $this->writeListeners[$streamId][2];
            unset($this->writeListeners[$streamId]);
            $cancelFunction();
        }
    }

    public function addPeriodicTimer($interval, $callback) {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        $timer = new ReactTimerFacade((float) $interval, $callback, true);
        $timer->_setCancelFunction(Loop::setInterval($timer->invoke(...), (float) $interval));
        return $timer;
    }

    public function addTimer($delay, $callback) {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        $timer = new ReactTimerFacade((float) $interval, $callback, false);
        $timer->_setCancelFunction(Loop::setTimeout($timer->invoke(...), (float) $delay));
        return $timer;
    }

    public function futureTick($listener) {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        Loop::defer($listener);
    }

    public function addSignal($signal, $listener) {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        $cancelFunction = Loop::addSignal($signal, $listener);
        $this->signalListeners[$signal][] = [ $listener, $cancelFunction ];
    }

    public function removeSignal($signal, $listener) {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        if (!isset($this->signalListeners[$signal])) {
            return;
        }

        foreach ($this->signalListeners[$signal] as $key => $info) {
            if ($info[0] == $listener) {
                $cancelFunction = $info[1];
                unset($this->signalListeners[$signal][$key]);
                $cancelFunction();
                return;
            }
        }
    }

    public function cancelTimer(TimerInterface $timer) {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        $timer->_cancel();
    }

    private static function closure(callable $callable): Closure {
        if ($this->debug) fwrite(STDOUT, __FUNCTION__." ".var_export(debug_backtrace(0, 8), true)."\n");
        if ($callable instanceof Closure) {
            return $callable;
        }
        return Closure::fromCallable($callable);
    }
}
