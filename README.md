moebius/loop-reactbridge
========================

Provides a "virtual react/event-loop" powered by the moebius event loop. The
moebius event loop can in turn be powered by another event loop implementation
such as Amphp.

The main purpose for this package is to enable using tried and tested components
such as `react/dns`, `react/socket` or `react/http` with Moebius.

If you are already using `react/event-loop` in your application, you do not need
this package. In that case `moebius/loop` will actually also use the React event
loop directy.


Valid combinations
------------------

 * `moebius/loop` + `moebius/loop-ampbridge` + `moebius/loop-reactbridge` means 
   that you can use both React and Amp components with Moebius.

 * `moebius/loop` + `react/event-loop` + `moebius/loop-ampbridge` means that you
   will be using the React event loop to run React, Amp and Moebius components.

 * `moebius/loop` + `amphp/amp` + `moebius/loop-reactbridge` means that you will
   be using the Amp event loop to run React, Amp and Moebius components.


Warning!
--------

Warning! This is not a production ready event loop implementation. It is rigorously
tested during development but has not reached any maturity. Please test it yourself
if you are curious.


Important!
----------

Moebius can use the `react/event-loop` implementation directly. This compatability
package SHOULD NOT be used if you actually intend to use the `react/event-loop`
as your backend.

In that case, you should instead simply install the react/event-loop:

```
composer require react/event-loop
```


Tested with:

 * react/stream
 * react/child-process

Currently supported event loops:

 * amphp/amp
 * react/event-loop

If neither of these event loop implementations are installed, a build-in pure PHP 
event loop implementation will be used.

