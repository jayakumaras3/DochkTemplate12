/**
 * Orientation Manager
 * Restricts landscape orientation on mobile phones only.
 * Allows landscape on tablets and desktops.
 * Displays a portrait-only message when user attempts to rotate on a phone.
 */
var OrientationManager = (function () {
    'use strict';

    var hideTimer = null;

    /**
     * Detect if the device is a mobile phone (not tablet or desktop)
     * Returns true for phones, false for tablets/desktops
     */
    function isMobilePhone() {
        var ua = navigator.userAgent.toLowerCase();
        var isPhone = false;
        var maxDim = Math.max(window.screen.width, window.screen.height);
        var isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
        var mobileUAFlag = !!(navigator.userAgentData && navigator.userAgentData.mobile);

        // iPadOS 13+ reports itself as Macintosh; exclude those devices.
        var isIPadOS = navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1;
        if (isIPadOS) {
            return false;
        }

        // Check for specific mobile phones
        if (/android/.test(ua)) {
            // Android: differentiate phone vs tablet by viewport class.
            isPhone = maxDim <= 932;
        } else if (/iphone|ipod/.test(ua)) {
            // iPhone or iPod - always treated as phone
            isPhone = true;
        } else if (/ipad/.test(ua)) {
            // iPad - treat as tablet
            isPhone = false;
        } else if (/(windows phone|blackberry|windows ce|webos|opera mini)/.test(ua)) {
            // Other mobile operating systems
            isPhone = true;
        } else if (mobileUAFlag && isTouchDevice && maxDim <= 932) {
            // UA Client Hints fallback for modern browsers
            isPhone = true;
        }

        return isPhone;
    }

    /**
     * Check if device orientation is landscape
     */
    function isLandscape() {
        return window.innerWidth > window.innerHeight;
    }

    /**
     * Show the portrait-only message overlay
     */
    function showPortraitMessage() {
        var messageEl = document.getElementById('orientationMessage');
        if (!messageEl) { return; }

        if (hideTimer !== null) {
            clearTimeout(hideTimer);
            hideTimer = null;
        }

        // Ensure the element is visible and properly styled
        messageEl.style.display = 'flex';
        messageEl.style.opacity = '1';
        messageEl.style.visibility = 'visible';
        messageEl.style.zIndex = '99999';
        
        // Force a reflow to ensure browser registers the changes
        void messageEl.offsetHeight;
    }

    /**
     * Hide the portrait-only message overlay
     */
    function hidePortraitMessage() {
        var messageEl = document.getElementById('orientationMessage');
        if (!messageEl) { return; }

        // Fade out
        messageEl.style.opacity = '0';
        
        // After fade completes, hide from layout
        hideTimer = setTimeout(function () {
            messageEl.style.display = 'none';
            messageEl.style.visibility = 'hidden';
            hideTimer = null;
        }, 400);
    }

    /**
     * Attempt to lock orientation to portrait using Screen Orientation API
     */
    function lockToPortrait() {
        // Try using the modern Screen Orientation API
        if (screen.orientation && screen.orientation.lock) {
            screen.orientation.lock('portrait-primary').catch(function (error) {
                console.log('Orientation lock not supported on this device');
            });
        }
    }

    /**
     * Handle orientation change event
     */
    function handleOrientationChange() {
        if (!isMobilePhone()) { return; }

        if (isLandscape()) {
            // Phone rotated to landscape - show overlay
            showPortraitMessage();
            // Attempt to lock back to portrait
            lockToPortrait();
        } else {
            // Phone back to portrait - hide overlay
            hidePortraitMessage();
        }
    }

    function attachOverlayWatcher() {
        var mainPage = document.getElementById('mainPage');
        if (!mainPage || typeof MutationObserver === 'undefined') { return; }

        var observer = new MutationObserver(function () {
            // ng-view content was replaced; re-apply visibility state.
            handleOrientationChange();
        });

        observer.observe(mainPage, {
            childList: true,
            subtree: true
        });
    }

    function ensureInitialApply() {
        var attempts = 0;
        var maxAttempts = 30;
        var intervalId = setInterval(function () {
            attempts += 1;
            handleOrientationChange();

            if (document.getElementById('orientationMessage') || attempts >= maxAttempts) {
                clearInterval(intervalId);
            }
        }, 100);
    }

    /**
     * Initialize orientation manager
     */
    function init() {
        if (!isMobilePhone()) {
            // Not a phone; no restrictions needed
            return;
        }

        // Try to lock to portrait on initialization
        lockToPortrait();

        // Listen for orientation changes
        window.addEventListener('orientationchange', handleOrientationChange, false);
        window.addEventListener('resize', handleOrientationChange, false);
        window.addEventListener('hashchange', handleOrientationChange, false);

        // Also handle screen.orientation API if available
        if (screen.orientation) {
            screen.orientation.addEventListener('change', function () {
                handleOrientationChange();
            });
        }

        attachOverlayWatcher();
        ensureInitialApply();

        // Initial check
        if (isLandscape()) {
            handleOrientationChange();
        }
    }

    // Public API
    return {
        init: init,
        isMobilePhone: isMobilePhone,
        isLandscape: isLandscape
    };
}());

// Auto-initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
        OrientationManager.init();
    });
} else {
    OrientationManager.init();
}
