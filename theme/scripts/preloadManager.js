/**
 * Preload Manager
 * Shows/hides the #preloader element with a 0.3s fade transition.
 * Deliberately does NOT touch element.className — the existing
 * contentController already manages that (preloaderteempcls / "").
 */
var PreloadManager = (function () {
    'use strict';

    var FADE_MS        = 200;   // transition duration — matches CSS
    var MIN_SHOW_MS    = 700;  // minimum visibility time — 2 seconds before page shows
    var PRELOADER_ID   = 'preloader';

    var _visible    = false;
    var _showTime   = 0;
    var _pending    = null;     // pending setTimeout handle
    var _onHiddenCallbacks = [];  // callbacks to fire once preloader is fully hidden

    function _el() {
        return document.getElementById(PRELOADER_ID);
    }

    /**
     * Pause all video elements and disable autoplay while preloader is visible.
     */
    function _pauseAllVideos() {
        var videos = document.querySelectorAll('video');
        for (var i = 0; i < videos.length; i++) {
            if (!videos[i].paused) {
                videos[i].pause();
            }
            // Disable autoplay to prevent auto-retry
            if (videos[i].getAttribute('autoplay')) {
                videos[i]._hadAutoplay = true;
                videos[i].removeAttribute('autoplay');
            }
        }
    }

    function _clearPending() {
        if (_pending !== null) {
            clearTimeout(_pending);
            _pending = null;
        }
    }

    /**
     * Show the preloader with a 0.3s fade-in.
     * Safe to call repeatedly — ignored if already visible.
     * Also pauses all videos and disables autoplay so no audio plays during transition.
     */
    function show() {
        if (_visible) { return; }

        _clearPending();

        var el = _el();
        if (!el) { return; }

        // Pause all videos and disable autoplay before showing preloader
        _pauseAllVideos();

        // Clear any pending onHidden callbacks from a previous navigation
        _onHiddenCallbacks = [];

        // 1. Make element visible but fully transparent
        el.style.display  = 'block';
        el.style.opacity  = '0';
        el.style.transition = 'none';

        // 2. Force a reflow so the browser registers opacity=0 before transitioning
        void el.offsetHeight;

        // 3. Apply transition and fade to full opacity
        el.style.transition = 'opacity ' + (FADE_MS / 1000) + 's ease-in-out';
        el.style.opacity    = '1';

        _visible  = true;
        _showTime = Date.now();
    }

    /**
     * Hide the preloader with a 0.3s fade-out.
     * Respects MIN_SHOW_MS so the spinner never flashes too briefly.
     * After hiding, fires all registered onHidden callbacks.
     */
    function hide() {
        if (!_visible) { return; }

        _clearPending();

        var elapsed = Date.now() - _showTime;
        var delay   = Math.max(0, MIN_SHOW_MS - elapsed);

        _pending = setTimeout(function () {
            var el = _el();
            if (!el) { return; }

            // Fade out
            el.style.transition = 'opacity ' + (FADE_MS / 1000) + 's ease-in-out';
            el.style.opacity    = '0';

            // After fade completes, actually hide from layout then fire onHidden callbacks
            _pending = setTimeout(function () {
                el.style.display = 'none';
                _pending = null;
                // Fire and clear all registered onHidden callbacks
                var cbs = _onHiddenCallbacks.slice();
                _onHiddenCallbacks = [];
                for (var i = 0; i < cbs.length; i++) {
                    try { cbs[i](); } catch (e) {}
                }
            }, FADE_MS);

            _visible = false;
        }, delay);
    }

    /**
     * Register a callback to be invoked once the preloader is fully hidden.
     * If the preloader is not currently visible, the callback fires immediately.
     */
    function onHidden(fn) {
        if (typeof fn !== 'function') { return; }
        if (!_visible && _pending === null) {
            fn();
        } else {
            _onHiddenCallbacks.push(fn);
        }
    }

    function isVisible() { return _visible; }

    // Public API
    return { show: show, hide: hide, isVisible: isVisible, onHidden: onHidden };
}());
