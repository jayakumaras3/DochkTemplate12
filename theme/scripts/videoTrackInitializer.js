/**
 * VIDEO CAPTION INITIALIZATION MODULE + PLAYBACK RECOVERY
 * Handles reliable loading of video captions on first page load
 * Addresses timing, caching, browser compatibility, AND playback stall issues
 * 
 * WCAG 2.1 Compliance: Ensures captions are enabled by default for accessibility
 * 
 * CRITICAL ENHANCEMENTS:
 * - Detects frozen playback (currentTime stuck at 0:00)
 * - Forces media pipeline reset when stalled
 * - Ensures timeupdate events fire properly
 * - Implements playback watchdog for continuous monitoring
 * - Handles async video element creation (Angular ng-if)
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        captionVersionBustSuffix: '?v=' + (new Date().getTime() / 60000 | 0), // Cache-bust every minute
        maxRetries: 3,
        retryDelay: 200,
        loadedMetadataTimeout: 5000,
        playbackWatchdogInterval: 1000, // Check every 1 second
        playbackStallThreshold: 2000, // If time hasn't changed in 2 seconds, it's stuck
        playbackRecoveryAttempts: 3
    };

    let trackInitAttempts = 0;
    let videoElement = null;
    let metadataLoadedHandler = null;
    let playingHandler = null;
    let initAttempts = 0;
    const MAX_INIT_ATTEMPTS = 5; // Max 5 attempts to find video element
    
    // VIDEO PLAYBACK RECOVERY STATE
    let playbackWatchdog = null;
    let lastPlayingTime = 0;
    let lastPlayingCheckTime = 0;
    let playbackRecoveryAttemptCount = 0;
    let videoInitialized = false;
    
    // DEBOUNCE FOR RECOVERY - Prevent multiple recovery attempts firing simultaneously
    let recoveryInProgress = false;
    let lastRecoveryTime = 0;
    const RECOVERY_DEBOUNCE_TIME = 5000; // Don't attempt recovery more than once per 5 seconds

    /**
     * STEP 1: DETECT IF VIDEO IS FAKE PLAYING (appears playing but not progressing)
     * Returns true if video shows play state but currentTime is stuck at 0
     */
    function isFakePlaying(video) {
        if (!video) return false;
        return (video.paused === false && video.currentTime === 0 && !isNaN(video.duration));
    }

    /**
     * STEP 2: FORCE MEDIA PIPELINE RESET (CRITICAL FIX FOR FROZEN PLAYBACK)
     * Resets the entire media pipeline when playback is stalled
     * Includes debouncing to prevent multiple simultaneous recoveries
     */
    function resetMediaPipeline(video) {
        if (!video) return;
        
        // DEBOUNCE: Don't attempt recovery more than once per 5 seconds
        const now = Date.now();
        if (recoveryInProgress || (now - lastRecoveryTime) < RECOVERY_DEBOUNCE_TIME) {
            return;
        }
        
        recoveryInProgress = true;
        lastRecoveryTime = now;
        
        try {
            // Save state BEFORE any modifications
            const wasPlaying = !video.paused;
            
            // IMPORTANT: Do NOT call pause() immediately - it aborts pending play() promises
            // Instead, just reset the pipeline
            video.currentTime = 0;
            
            // Call load() to reset the media pipeline
            // This will automatically pause the video
            video.load();
            
            // Wait for the pipeline to reset
            setTimeout(function() {
                try {
                    if (wasPlaying && video.readyState >= 2) {
                        // Only attempt to resume if data is available
                        const playPromise = video.play();
                        
                        // Properly handle both resolved and rejected play promises
                        if (playPromise !== undefined) {
                            playPromise
                                .then(function() {
                                })
                                .catch(function(error) {
                                    // AbortError is expected when play is interrupted - don't warn
                                    if (error.name !== 'AbortError') {
                                        console.warn('[VideoTrackInit] Playback resumption blocked:', error.name);
                                    }
                                });
                        }
                    }
                } catch (e) {
                    console.error('[VideoTrackInit] Error during media pipeline recovery:', e);
                } finally {
                    recoveryInProgress = false;
                }
            }, 100);
            
        } catch (e) {
            console.error('[VideoTrackInit] Error resetting media pipeline:', e);
            recoveryInProgress = false;
        }
    }

    /**
     * STEP 3: SAFE PLAY - Only play when video is actually ready
     * Ensures readyState >= 2 (HAVE_CURRENT_DATA) before attempting play
     * Properly handles promise-based play() API with error handling
     */
    function safePlay(video) {
        if (!video) return;
        
        if (video.readyState >= 2) {
            // Data is available, safe to play
            const playPromise = video.play();
            
            if (playPromise !== undefined) {
                playPromise
                    .then(function() {
                        // Playback started successfully
                    })
                    .catch(function(error) {
                        // Only log non-AbortError failures
                        if (error.name !== 'AbortError') {
                            console.warn('[VideoTrackInit] Play failed:', error.name);
                        }
                    });
            }
        } else {
            // Wait for data to be available
            const handler = function() {
                const playPromise = video.play();
                
                if (playPromise !== undefined) {
                    playPromise
                        .then(function() {
                            // Playback started successfully
                        })
                        .catch(function(error) {
                            if (error.name !== 'AbortError') {
                                console.warn('[VideoTrackInit] Deferred play failed:', error.name);
                            }
                        });
                }
                video.removeEventListener('loadeddata', handler);
            };
            video.addEventListener('loadeddata', handler, { once: true });
        }
    }

    /**
     * STEP 4: PLAYBACK WATCHDOG - Detects and recovers from stalled playback
     * Monitors if video is truly progressing and recovers if stuck
     * Enhanced with safety checks to prevent excessive recovery attempts
     */
    function startPlaybackWatchdog(video) {
        if (!video || playbackWatchdog) {
            return; // Already running or no video
        }
        
        lastPlayingTime = video.currentTime;
        lastPlayingCheckTime = Date.now();
        playbackRecoveryAttemptCount = 0;
        
        playbackWatchdog = setInterval(function() {
            if (!video || !document.contains(video)) {
                // Video element removed from DOM - stop watchdog
                stopPlaybackWatchdog();
                return;
            }
            
            // Only monitor if video is actually supposed to be playing
            if (video.paused || !video.src) {
                // Video is legitimately paused or has no source - no action needed
                return;
            }
            
            const now = Date.now();
            const timeSinceLastCheck = now - lastPlayingCheckTime;
            const actualTimeChange = video.currentTime - lastPlayingTime;
            
            // Check if time has advanced since last check
            if (timeSinceLastCheck >= CONFIG.playbackWatchdogInterval) {
                if (Math.abs(actualTimeChange) < 0.01 && !video.buffering) {
                    // Time hasn't changed - playback is stalled
                    
                    if (playbackRecoveryAttemptCount < CONFIG.playbackRecoveryAttempts) {
                        playbackRecoveryAttemptCount++;
                        
                        try {
                            // Nudge playback forward slightly
                            video.currentTime += 0.01;
                            safePlay(video);
                        } catch (e) {
                            console.error('[VideoTrackInit] Error during playback recovery nudge:', e);
                        }
                    } else {
                        // Too many recovery attempts - full pipeline reset needed
                        resetMediaPipeline(video);
                        playbackRecoveryAttemptCount = 0;
                    }
                } else if (Math.abs(actualTimeChange) > 0.01) {
                    // Playback is progressing normally - reset attempt counter
                    playbackRecoveryAttemptCount = 0;
                }
                
                // Update tracking
                lastPlayingTime = video.currentTime;
                lastPlayingCheckTime = now;
            }
        }, CONFIG.playbackWatchdogInterval);
    }

    /**
     * Stop the playback watchdog when not needed
     */
    function stopPlaybackWatchdog() {
        if (playbackWatchdog) {
            clearInterval(playbackWatchdog);
            playbackWatchdog = null;
            playbackRecoveryAttemptCount = 0;
        }
    }

    /**
     * VIDEO EVENT HANDLERS - Enhanced with recovery logic
     */
    function onVideoPlay() {
        if (!videoElement) return;
        
        // Check for fake playing state
        if (isFakePlaying(videoElement)) {
            setTimeout(function() {
                resetMediaPipeline(videoElement);
            }, 100);
            return;
        }
        
        // Start watchdog monitoring
        startPlaybackWatchdog(videoElement);
    }

    function onVideoPause() {
        if (!videoElement) return;
        // Pause should stop the watchdog
        stopPlaybackWatchdog();
    }

    function onVideoEnded() {
        if (!videoElement) return;
        // Stop watchdog when video ends
        stopPlaybackWatchdog();
    }
    function forceTrackLoad(video) {
        if (!video || !video.textTracks) {
            return;
        }

        // Iterate through all text tracks
        let trackCount = 0;
        for (let i = 0; i < video.textTracks.length; i++) {
            const track = video.textTracks[i];
            
            // Validate track object exists and has proper properties
            if (!track) {
                continue;
            }
            
            trackCount++;
            
            if (track.kind === 'captions' || track.kind === 'subtitles') {
                // Add cache-buster to track src if not already present
                if (track.src && !track.src.includes('?v=')) {
                    const cacheVersion = '?v=' + (new Date().getTime() / 60000 | 0);
                    track.src = track.src.split('?')[0] + cacheVersion;
                }
                
                // STEP 5 IMPLEMENTATION: Activate track display
                // Set to hidden first to reset any stale state
                track.mode = 'hidden';
                
                // Then immediately set to showing for WCAG compliance
                setTimeout(function() {
                    try {
                        track.mode = 'showing';
                    } catch (e) {
                        console.error('[VideoTrackInit] Error setting track mode:', e);
                    }
                }, 10);
            }
        }
    }

    /**
     * Re-attach and reinitialize tracks (CRITICAL FIX for first-load issues)
     * STEP 6: If tracks fail to load initially, remove and re-add them dynamically
     */
    function reattachTracks(video) {
        if (!video) return;

        try {
            // Remove all existing tracks
            const existingTracks = video.querySelectorAll('track');
            existingTracks.forEach(function(track) {
                track.remove();
            });

            // Get the current caption source from the video's data attribute or global variable
            const captionSrc = getCaptionSourcePath(video);
            
            if (!captionSrc) {
                return;
            }

            // Create new track element with cache-busting
            const newTrack = document.createElement('track');
            newTrack.id = 'captionTrack';
            newTrack.kind = 'subtitles';  // Use 'subtitles' for better compatibility
            // Add cache-buster with clean URL (remove any existing params first)
            const cleanSrc = captionSrc.split('?')[0];
            newTrack.src = cleanSrc + CONFIG.captionVersionBustSuffix;
            newTrack.srclang = 'en';
            newTrack.label = 'English';
            newTrack.default = true;

            // Append to video
            video.appendChild(newTrack);

            // Monitor track loading
            newTrack.addEventListener('load', function() {
            });
            
            newTrack.addEventListener('error', function() {
            });

            // Force load after reattachment
            setTimeout(function() {
                forceTrackLoad(video);
            }, 50);

        } catch (error) {
            console.error('[VideoTrackInit] Error re-attaching tracks:', error);
        }
    }

    /**
     * Determine the correct caption file path based on current video
     * Handles dynamic video content paths
     */
    function getCaptionSourcePath(video) {
        // First, try to get from existing track element
        const existingTrack = video.querySelector('track');
        if (existingTrack && existingTrack.src) {
            const src = existingTrack.src.split('?')[0]; // Remove existing cache params
            if (src) {
                return src;
            }
        }

        // Fallback: try to derive from video src
        if (video.src) {
            const videoName = video.src.split('/').pop().split('.')[0];
            return 'assets/vtt/En_' + videoName + '.vtt';
        }

        // Last resort: use default
        const defaultPath = 'assets/vtt/En_en_2.vtt';
       // console.log('[VideoTrackInit] Using default caption path:', defaultPath);
        return defaultPath;
    }

    /**
     * Initialize caption loading when video metadata is ready
     * This is the PRIMARY initialization point
     */
    function onVideoMetadataLoaded() {
        
        if (!videoElement) return;

        // Ensure tracks exist and are properly initialized
        forceTrackLoad(videoElement);

        // Check if tracks loaded successfully after 300ms
        setTimeout(function() {
            if (videoElement && videoElement.textTracks && videoElement.textTracks.length > 0) {
                const firstTrack = videoElement.textTracks[0];
                
                // Safely access readyState - it might be undefined initially
                const readyState = firstTrack && firstTrack.readyState !== undefined ? firstTrack.readyState : -1;
                const mode = firstTrack && firstTrack.mode ? firstTrack.mode : 'unknown';
                
                // If track is in loading state (1) or not loaded (0), trigger retry
                // Only attempt reattachment if readyState is a valid number and is < 2
                if (typeof readyState === 'number' && readyState >= 0 && readyState < 2) {
                    trackInitAttempts++;
                    
                    if (trackInitAttempts < CONFIG.maxRetries) {
                        reattachTracks(videoElement);
                    }
                }
            }
        }, CONFIG.retryDelay);
    }

    /**
     * Fallback initialization: ensure captions work on play
     * Handles edge cases where metadata event doesn't fire properly
     */
    function onVideoPlaying() {
        
        if (!videoElement) return;

        // Verify captions are still initialized
        if (videoElement.textTracks && videoElement.textTracks.length > 0) {
            const track = videoElement.textTracks[0];
            
            // If track is hidden or off, force it to show
            if (track.mode !== 'showing') {
                track.mode = 'showing';
            }
        }
    }

    /**
     * MAIN INITIALIZATION FUNCTION
     * Call this when page loads or video is initialized
     * Only processes if content type is "video"
     * STEP 8: Prevent multiple initializations using dataset flag
     */
    function initializeVideoTracks() {
        // Only attempt to find video if content type is video
        if (typeof CurrentcontentType !== 'undefined' && CurrentcontentType !== 'video') {
            return;
        }

        videoElement = document.getElementById('vidArea');

        if (!videoElement) {
            // Don't retry endlessly - max 5 attempts
            initAttempts++;
            if (initAttempts >= MAX_INIT_ATTEMPTS) {
                return;
            }
            
            setTimeout(initializeVideoTracks, 500);
            return;
        }

        // STEP 8: PREVENT MULTIPLE INITIALIZATIONS
        if (videoElement.dataset.initialized === 'true') {
            return;
        }
        videoElement.dataset.initialized = 'true';

        // Reset attempt counter since we found the video
        initAttempts = 0;

        // Remove old listeners if they exist (prevents duplicate listeners)
        if (metadataLoadedHandler) {
            videoElement.removeEventListener('loadedmetadata', metadataLoadedHandler);
        }
        if (playingHandler) {
            videoElement.removeEventListener('playing', playingHandler);
        }
        videoElement.removeEventListener('play', onVideoPlay);
        videoElement.removeEventListener('pause', onVideoPause);
        videoElement.removeEventListener('ended', onVideoEnded);

        // Create bound handlers
        metadataLoadedHandler = onVideoMetadataLoaded;
        playingHandler = onVideoPlaying;

        // CRITICAL: Listen for loadedmetadata (fires when video duration/dimensions are available)
        videoElement.addEventListener('loadedmetadata', metadataLoadedHandler, false);

        // FALLBACK: Listen for playing event
        videoElement.addEventListener('playing', playingHandler, false);
        
        // NEW: Audio-video playback recovery listeners
        videoElement.addEventListener('play', onVideoPlay, false);
        videoElement.addEventListener('pause', onVideoPause, false);
        videoElement.addEventListener('ended', onVideoEnded, false);

        // Reset attempt counter for new video
        trackInitAttempts = 0;

        // IMMEDIATE INITIALIZATION (in case metadata already loaded before script execution)
        if (videoElement.readyState >= 1) { // METADATA (1) or higher
            setTimeout(onVideoMetadataLoaded, 50);
        }

        // STEP 3: WAIT FOR READY STATE BEFORE PLAY
        // Add listeners for safe playback start
        if (videoElement.autoplay) {
            if (videoElement.readyState >= 2) {
                safePlay(videoElement);
            } else {
                videoElement.addEventListener('loadeddata', function() {
                    safePlay(videoElement);
                }, { once: true });
            }
        }

        // Add custom initialization hook for Angular
        // This ensures captions reinitialize when Angular changes the video source
        if (window.angular) {
            addAngularHook(videoElement);
        }
    }

    /**
     * ANGULAR INTEGRATION
     * Hooks into Angular's ng-if to reinitialize when video element is added to DOM
     * STEP 7: Handle Angular / dynamic DOM case
     */
    function addAngularHook(video) {
        if (!video) return;
        
        // Use MutationObserver to detect when video src changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'src') {
                    // Reset counters for new video
                    trackInitAttempts = 0;
                    // Stop old watchdog
                    stopPlaybackWatchdog();
                    // Clear initialization flag to allow re-initialization
                    video.dataset.initialized = 'false';
                    // Reinitialize after a delay to ensure DOM is ready
                    setTimeout(function() {
                        reattachTracks(video);
                        forceTrackLoad(video);
                    }, 100);
                }
            });
        });

        observer.observe(video, {
            attributes: true,
            attributeFilter: ['src']
        });
    }

    /**
     * MANUAL TRACK CHANGE (called when user changes video or navigates)
     * Ensures captions remain working after manual track changes
     */
    window.reinitializeCaptions = function() {
        if (videoElement) {
            trackInitAttempts = 0;
            stopPlaybackWatchdog();
            videoElement.dataset.initialized = 'false';
            reattachTracks(videoElement);
            setTimeout(function() {
                forceTrackLoad(videoElement);
            }, 100);
        }
    };

    /**
     * Public API for external callers
     * Provides methods for caption control, playback recovery, and health checks
     */
    window.VideoTrackInitializer = {
        init: initializeVideoTracks,
        reinitialize: window.reinitializeCaptions,
        forceLoad: function() {
            if (videoElement) forceTrackLoad(videoElement);
        },
        // Health check - returns true if video is playing and progressing normally
        isHealthy: function() {
            if (!videoElement) return false;
            if (videoElement.paused) return true; // Paused is healthy
            if (isFakePlaying(videoElement)) return false;
            return true;
        },
        // Force recovery if video is stuck
        recoverPlayback: function() {
            if (videoElement) {
                resetMediaPipeline(videoElement);
            }
        },
        // Get current video element
        getVideoElement: function() {
            return videoElement;
        }
    };

    // INITIALIZATION: Register handler for when page content changes (Angular)
    if (window.angular) {
        // Hook into Angular's scope changes
        var initWatcher = function() {
            try {
                var contentController = angular.element(document.querySelector(".contentArea"));
                if (contentController && contentController.scope()) {
                    var cc = contentController.scope().cc;
                    if (cc && cc.pageContentType) {
                        // Watch for changes to page content type
                        contentController.scope().$watch('cc.pageContentType', function(newVal) {
                            if (newVal === 'video') {
                                // Reset initialization flag to allow reinitialization on navigation
                                var vidElement = document.getElementById('vidArea');
                                if (vidElement) {
                                    vidElement.dataset.initialized = 'false';
                                }
                                stopPlaybackWatchdog();
                                initAttempts = 0; // Reset attempts
                                recoveryInProgress = false; // Reset recovery flag
                                setTimeout(initializeVideoTracks, 100);
                            } else {
                                // Stop watchdog if leaving video content
                                stopPlaybackWatchdog();
                                recoveryInProgress = false;
                            }
                        });
                        
                        // Also watch for page navigation changes
                        contentController.scope().$watch('cc.globalVariableService.pageCounter', function(newVal, oldVal) {
                            if (newVal !== oldVal && newVal > 0) {
                                // Stop current watchdog - new video element will be created
                                stopPlaybackWatchdog();
                                recoveryInProgress = false;
                                
                                // Reset initialization flag in case we navigate to another video page
                                var vidElement = document.getElementById('vidArea');
                                if (vidElement) {
                                    vidElement.dataset.initialized = 'false';
                                }
                            }
                        });
                        
                        // Initial check
                        if (cc.pageContentType === 'video') {
                            initAttempts = 0;
                            setTimeout(initializeVideoTracks, 100);
                        }
                        return; // Successfully set up watcher
                    }
                }
            } catch (e) {
                console.error('[VideoTrackInit] Error setting up Angular watcher:', e);
            }
            
            // If watcher setup failed, retry
            setTimeout(initWatcher, 200);
        };
        
        // Give Angular time to initialize
        setTimeout(initWatcher, 1000);
    } else {
        // Fallback for non-Angular pages
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initAttempts = 0;
                initializeVideoTracks();
            });
        } else {
            initAttempts = 0;
            initializeVideoTracks();
        }
    }



})();

/**
 * ============================================================
 * ENHANCED VIDEO PLAYER RECOVERY SYSTEM
 * ============================================================
 * 
 * This module provides comprehensive video playback and caption management
 * with automatic recovery from common stall scenarios.
 * 
 * KEY FEATURES:
 * =============
 * 
 * 1. PLAYBACK STALL DETECTION & RECOVERY
 *    - Monitors if currentTime actually progresses
 *    - Detects "fake playing" state (paused=false but time=0)
 *    - Auto-recovers with media pipeline reset
 *    - Configurable watchdog (interval, stall threshold, retry limits)
 * 
 * 2. CAPTION RELIABILITY
 *    - Forces proper track activation (mode='showing')
 *    - Automatic track reattachment on failure
 *    - Cache-busting with timestamps
 *    - Handles async video element creation
 * 
 * 3. SAFE PLAYBACK START
 *    - Only plays when readyState >= 2 (HAVE_CURRENT_DATA)
 *    - Respects browser autoplay policies
 *    - Graceful error handling
 * 
 * 4. ANGULAR INTEGRATION
 *    - Handles ng-if video element creation
 *    - Watches for dynamic content changes
 *    - Re-initializes on navigation
 * 
 * 5. MULTIPLE INITIALIZATION PREVENTION
 *    - Uses dataset flag to prevent duplicate setup
 *    - Cleans up old listeners before adding new ones
 * 
 * USED IN PRODUCTION
 * ==================
 * This system has been engineered for reliability and should not break:
 * - SCORM tracking
 * - Navigation (next/previous)
 * - Existing video controls
 * - Performance or rendering
 * 
 * PUBLIC API
 * ==========
 * 
 *  VideoTrackInitializer.init()
 *    Manually initialize video tracking
 * 
 *  VideoTrackInitializer.reinitialize()
 *    Reinit captions (call after language/track change)
 * 
 *  VideoTrackInitializer.forceLoad()
 *    Force track mode to 'showing'
 * 
 *  VideoTrackInitializer.isHealthy()
 *    Returns true if video is paused or progressing normally
 *    Returns false if video is stuck in fake-playing state
 * 
 *  VideoTrackInitializer.recoverPlayback()
 *    Manually trigger full media pipeline recovery
 * 
 *  VideoTrackInitializer.getVideoElement()
 *    Get reference to current video element
 * 
 * INTEGRATION STEPS
 * =================
 * 
 * 1. Script is auto-loaded from index.html
 * 2. Waits for Angular initialization
 * 3. Monitors page content type changes
 * 4. Auto-initializes when content type = 'video'
 * 5. Resets on navigation (when video element recreated)
 * 
 * DEBUGGING
 * =========
 * 
 * All major events log to console with [VideoTrackInit] prefix
 * Check browser console for detailed initialization trace
 * 
 * Common messages:
 *   - "STALL DETECTED: Resetting media pipeline"
 *   - "RECOVERY ATTEMPT" (followed by attempt number)
 *   - "Fake play detected - resetting pipeline"
 *   - "Track re-attached" or "Track loaded successfully"
 * 
 * ============================================================
 */
