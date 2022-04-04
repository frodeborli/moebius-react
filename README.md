moebius/loop-reactbridge
========================

Makes it possible to run ReactPHP components on different event loop implementations.

Tested with:

 * react/stream
 * react/child-process

Currently supported event loops:

 * amphp/amp
 * react/event-loop

If neither of these event loop implementations are installed, a build-in pure PHP 
event loop implementation will be used.

