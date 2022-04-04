<?php
namespace Moebius\React;

use React\EventLoop\TimerInterface;

class ReactTimerFacade implements TimerInterface {
    private float $interval;
    private $callback;
    private bool $periodic;
    private $cancelFunction;
    private bool $cancelled = false;

    public function __construct(float $interval, callable $callback, bool $periodic) {
        $this->interval = $interval;
        $this->callback = $callback;
        $this->periodic = $periodic;
    }

    /**
     * Set the function which cancels this timer from running in the event loop
     *
     * @internal
     * @param callable $cancelFunction
     */
    public function _setCancelFunction(callable $cancelFunction) {
        $this->cancelFunction;
    }

    /**
     * Cancel this timer from running in the event loop
     *
     * @internal
     */
    public function _cancel() {
        if (!$this->cancelled) {
            ($this->cancelFunction)();
            $this->cancelled = true;
        }
    }

    public function invoke() {
        if ($this->cancelled) {
            return;
        }
        ($this->callback)($this);
    }

    public function getInterval() {
        return $this->interval;
    }

    public function getCallback() {
        return $this->callback;
    }

    public function isPeriodic() {
        return $this->periodic;
    }
}
