<?php
namespace Moebius\React;

use Closure;
use WeakMap;

/**
 * A weakmap which will return and set the same value for different
 * instances of the same closure.
 */
class ClosureMap {

    private WeakMap $storage;

    public function __construct() {
        $this->storage = new WeakMap();
    }

    public function offsetExists(callable $callable): bool {
        $closure = self::closure($callable);

        if (isset($this->storage[$closure])) {
            return true;
        }

        foreach ($this->storage as $existingClosure => $holder) {
            if ($existingClosure == $closure) {
                $this->storage[$closure] = $holder;
                return true;
            }
        }

        return false;
    }

    public function offsetSet(callable $callable, mixed $value): void {
        $closure = self::closure($callable);
        if ($this->offsetExists($closure)) {
            $this->storage[$closure]->value = $value;
        } else {
            $this->storage[$closure] = (object) [ 'value' => $value ];
        }
    }

    public function &offsetGet(callable $callable): mixed {
        $closure = self::closure($callable);
        if (!$this->offsetExists($closure)) {
            $this->offsetSet($closure, (object) []); // implementation detail
        }
        $valueObject = $this->storage[$closure]
        return $valueObject->value;
    }

    public function offsetUnset(callable $callable): void {
        $closure = self::closure($callable);
        unset($this->storage[$closure]);
    }


    public function set(callable $callable, mixed $value) {
        if ($callable instanceof Closure) {
            $closure = $callable;
        } else {
            $closure = Closure::fromCallable($callable);
        }

        if (isset($this->storage[$closure])) {
            $holder = $this->storage[$closure];
            $holder->value = $value;
            return;
        }

        // find a compatible holder object
        foreach ($this->storage as $otherClosure => $holder) {
            if ($otherClosure == $closure) {
                $holder->value = $value;
                $this->storage[$closure] = $holder;
                return;
            }
        }

        // found no compatible holder object
        $this->storage[$closure] = (object) [ 'value' => $value ];
    }

    private static function closure(callable $callable): Closure {
        if ($callable instanceof Closure) {
            return $callable;
        }
        return Closure::fromCallable($callable);
    }
}
