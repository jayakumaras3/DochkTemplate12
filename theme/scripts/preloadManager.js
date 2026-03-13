/**
 * Preload Manager
 * Shows/hides the #preloader element with a 0.3s fade transition.
 * Deliberately does NOT touch element.className — the existing
 * contentController already manages that (preloaderteempcls / "").
 */
var PreloadManager = (function () {
    'use strict';

    var FADE_MS        = 200;   // transition duration — matches CSS
    var MIN_SHOW_MS    = 200;   // minimum visibility time (content loading delayed 1sec from nav handlers)
    var PRELOADER_ID   = 'preloader';

    var _visible    = false;
    var _showTime   = 0;
    var _pending    = null;     // pending setTimeout handle

    function _el() {
        return document.getElementById(PRELOADER_ID);
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
     */
    function show() {
        if (_visible) { return; }

        _clearPending();

        var el = _el();
        if (!el) { return; }

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

            // After fade completes, actually hide from layout
            _pending = setTimeout(function () {
                el.style.display = 'none';
                _pending = null;
            }, FADE_MS);

            _visible = false;
        }, delay);
    }

    function isVisible() { return _visible; }

    // Public API
    return { show: show, hide: hide, isVisible: isVisible };
}());
