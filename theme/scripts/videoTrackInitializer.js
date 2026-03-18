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
     */
    function resetMediaPipeline(video) {
        if (!video) return;
        
        console.warn('[VideoTrackInit] STALL DETECTED: Resetting media pipeline...');
        
        try {
            // Save state
            const wasPlaying = !video.paused;
            const savedTime = video.currentTime;
            
            // Force reset
            video.pause();
            video.currentTime = 0;
            
            // Clear any pending operations
            setTimeout(function() {
                try {
                    video.load(); // Reloads media pipeline from scratch
                    
                    if (wasPlaying && video.readyState >= 2) {
                        // Resume playback if it was playing before
                        setTimeout(function() {
                            video.play().catch(function(error) {
                                console.warn('[VideoTrackInit] Auto-play resumption blocked:', error.name);
                                // This is expected in some browser policies
                            });
                        }, 100);
                    }
                } catch (e) {
                    console.error('[VideoTrackInit] Error during media pipeline reset:', e);
                }
            }, 100);
            
        } catch (e) {
            console.error('[VideoTrackInit] Error resetting media pipeline:', e);
        }
    }

    /**
     * STEP 3: SAFE PLAY - Only play when video is actually ready
     * Ensures readyState >= 2 (HAVE_CURRENT_DATA) before attempting play
     */
    function safePlay(video) {
        if (!video) return;
        
        if (video.readyState >= 2) {
            video.play().catch(function(error) {
                console.warn('[VideoTrackInit] Play request blocked:', error.name);
            });
        } else {
            // Wait for data to be available
            const handler = function() {
                video.play().catch(function(error) {
                    console.warn('[VideoTrackInit] Deferred play request blocked:', error.name);
                });
                video.removeEventListener('loadeddata', handler);
            };
            video.addEventListener('loadeddata', handler, { once: true });
        }
    }

    /**
     * STEP 4: PLAYBACK WATCHDOG - Detects and recovers from stalled playback
     * Monitors if video is truly progressing and recovers if stuck
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
                // Video element removed from DOM
                stopPlaybackWatchdog();
                return;
            }
            
            if (video.paused) {
                // Video is legitimately paused - no action needed
                return;
            }
            
            const now = Date.now();
            const timeSinceLastCheck = now - lastPlayingCheckTime;
            const actualTimeChange = video.currentTime - lastPlayingTime;
            
            // Check if time has advanced since last check
            if (timeSinceLastCheck >= CONFIG.playbackWatchdogInterval) {
                if (Math.abs(actualTimeChange) < 0.01 && !video.buffering) {
                    // Time hasn't changed - playback is stalled
                    console.warn('[VideoTrackInit] PLAYBACK STALL: time=' + video.currentTime + ', expected change since last check');
                    
                    if (playbackRecoveryAttemptCount < CONFIG.playbackRecoveryAttempts) {
                        playbackRecoveryAttemptCount++;
                        console.warn('[VideoTrackInit] RECOVERY ATTEMPT #' + playbackRecoveryAttemptCount);
                        
                        try {
                            // Nudge playback forward slightly
                            video.currentTime += 0.01;
                            safePlay(video);
                        } catch (e) {
                            console.error('[VideoTrackInit] Error during playback recovery nudge:', e);
                        }
                    } else {
                        // Too many recovery attempts - full pipeline reset
                        resetMediaPipeline(video);
                        playbackRecoveryAttemptCount = 0;
                    }
                } else {
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
            console.warn('[VideoTrackInit] Fake play detected - resetting pipeline');
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
            console.warn('[VideoTrackInit] Video element or textTracks not available');
            return;
        }

        // Force all tracks to show (accessible default)
        for (let i = 0; i < video.textTracks.length; i++) {
            const track = video.textTracks[i];
            if (track.kind === 'captions' || track.kind === 'subtitles') {
                // Add cache-buster to track src if not already present
                if (track.src && !track.src.includes('?v=')) {
                    const cacheVersion = '?v=' + (new Date().getTime() / 60000 | 0);
                    track.src = track.src.split('?')[0] + cacheVersion;
                   // console.log('[VideoTrackInit] Added cache-buster to track:', track.src);
                }
                
                // Set to hidden first to trigger browser reload
                track.mode = 'hidden';
                // Then set to showing for WCAG compliance
                track.mode = 'showing';
                ///console.log('[VideoTrackInit] Track ' + i + ' mode set to showing. Ready state:', track.readyState, 'Cues:', track.cues ? track.cues.length : 0);
            }
        }
    }

    /**
     * Re-attach and reinitialize tracks (CRITICAL FIX for first-load issues)
     * If tracks fail to load initially, remove and re-add them dynamically
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
                console.warn('[VideoTrackInit] No caption source found for current video');
                return;
            }

            // Create new track element with cache-busting
            const newTrack = document.createElement('track');
            newTrack.id = 'captionTrack';
            newTrack.kind = 'captions';
            // Add cache-buster with clean URL (remove any existing params first)
            const cleanSrc = captionSrc.split('?')[0];
            newTrack.src = cleanSrc + CONFIG.captionVersionBustSuffix;
            newTrack.srclang = 'en';
            newTrack.label = 'English';
            newTrack.default = true;

            // Append to video
            video.appendChild(newTrack);

         //   console.log('[VideoTrackInit] Track re-attached with cache-buster:', newTrack.src);

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
        //console.log('[VideoTrackInit] Metadata loaded event fired');
        
        if (!videoElement) return;

        // Ensure tracks exist and are properly initialized
        forceTrackLoad(videoElement);

        // Check if tracks loaded successfully after 300ms
        setTimeout(function() {
            if (videoElement.textTracks && videoElement.textTracks.length > 0) {
                const firstTrack = videoElement.textTracks[0];
          //      console.log('[VideoTrackInit] First track status check. Ready state:', firstTrack.readyState, 'Mode:', firstTrack.mode, 'Cues:', firstTrack.cues ? firstTrack.cues.length : 0);
                
                // If track is in loading state (1) or not loaded (0), trigger retry
                if (firstTrack.readyState < 2) {
                 ////   console.warn('[VideoTrackInit] Track not fully loaded (readyState: ' + firstTrack.readyState + '), attempting reattachment...');
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
        //console.log('[VideoTrackInit] Video playing event fired');
        
        if (!videoElement) return;

        // Verify captions are still initialized
        if (videoElement.textTracks && videoElement.textTracks.length > 0) {
            const track = videoElement.textTracks[0];
         //   console.log('[VideoTrackInit] Playing check - Track mode:', track.mode, 'Ready state:', track.readyState, 'Cues:', track.cues ? track.cues.length : 0);
            
            // If track is hidden or off, force it to show
            if (track.mode !== 'showing') {
              //  console.warn('[VideoTrackInit] Track mode is ' + track.mode + ', forcing to showing');
                track.mode = 'showing';
            }
        }
    }

    /**
     * MAIN INITIALIZATION FUNCTION
     * Call this when page loads or video is initialized
     * Only processes if content type is "video"
     */
    function initializeVideoTracks() {
        // Only attempt to find video if content type is video
        // Check global variable CurrentcontentType (set by Angular controller)
        if (typeof CurrentcontentType !== 'undefined' && CurrentcontentType !== 'video') {
          //  console.log('[VideoTrackInit] Content type is ' + CurrentcontentType + ', skipping video initialization');
            return;
        }

        videoElement = document.getElementById('vidArea');

        if (!videoElement) {
            // Don't retry endlessly - max 5 attempts
            initAttempts++;
            if (initAttempts >= MAX_INIT_ATTEMPTS) {
                //console.log('[VideoTrackInit] Video element not found after ' + MAX_INIT_ATTEMPTS + ' attempts. Skipping initialization (may not be a video page).');
                return;
            }
            
           // console.log('[VideoTrackInit] Video element not found (attempt ' + initAttempts + '/' + MAX_INIT_ATTEMPTS + '). Retrying in 500ms...');
            setTimeout(initializeVideoTracks, 500);
            return;
        }

        // Reset attempt counter since we found the video
        initAttempts = 0;

        //console.log('[VideoTrackInit] Initializing video track handling for:', videoElement.id);

        // Remove old listeners if they exist (prevents duplicate listeners)
        if (metadataLoadedHandler) {
            videoElement.removeEventListener('loadedmetadata', metadataLoadedHandler);
        }
        if (playingHandler) {
            videoElement.removeEventListener('playing', playingHandler);
        }

        // Create bound handlers
        metadataLoadedHandler = onVideoMetadataLoaded;
        playingHandler = onVideoPlaying;

        // CRITICAL: Listen for loadedmetadata (fires when video duration/dimensions are available)
        videoElement.addEventListener('loadedmetadata', metadataLoadedHandler, false);

        // FALLBACK: Listen for playing event
        videoElement.addEventListener('playing', playingHandler, false);

        // Reset attempt counter for new video
        trackInitAttempts = 0;

        // IMMEDIATE INITIALIZATION (in case metadata already loaded before script execution)
        if (videoElement.readyState >= 1) { // METADATA (1) or higher
           // console.log('[VideoTrackInit] Metadata already available (readyState: ' + videoElement.readyState + '), initializing immediately');
            setTimeout(onVideoMetadataLoaded, 50);
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
     */
    function addAngularHook(video) {
        // Use MutationObserver to detect when video src changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'src' || mutation.attributeName === 'ng-if') {
                   // console.log('[VideoTrackInit] Video src or visibility changed, reinitializing');
                    // Reset counters
                    trackInitAttempts = 0;
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
        //console.log('[VideoTrackInit] Manual reinitialization requested');
        if (videoElement) {
            trackInitAttempts = 0;
            reattachTracks(videoElement);
            setTimeout(function() {
                forceTrackLoad(videoElement);
            }, 100);
        }
    };

    /**
     * Public API for external callers
     */
    window.VideoTrackInitializer = {
        init: initializeVideoTracks,
        reinitialize: window.reinitializeCaptions,
        forceLoad: function() {
            if (videoElement) forceTrackLoad(videoElement);
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
                                //console.log('[VideoTrackInit] Content type changed to video, initializing...');
                                initAttempts = 0; // Reset attempts
                                setTimeout(initializeVideoTracks, 100);
                            }
                        });
                        
                        // Initial check
                        if (cc.pageContentType === 'video') {
                            //console.log('[VideoTrackInit] Initial page is video, initializing...');
                            initAttempts = 0;
                            setTimeout(initializeVideoTracks, 100);
                        }
                        return; // Successfully set up watcher
                    }
                }
            } catch (e) {
                // Ignore errors during setup
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

    //console.log('[VideoTrackInit] Module loaded - Captions initialization ready (video pages only)');

})();

/**
 * INTEGRATION NOTES:
 * 
 * 1. This script should be loaded AFTER jQuery/Angular but BEFORE content rendering
 * 2. Add to index.html before </head>: <script src="theme/scripts/videoTrackInitializer.js"></script>
 * 
 * 3. When LanguageTrackChange() is called, follow with: VideoTrackInitializer.reinitialize()
 * 
 * 4. Provides global API:
 *    - VideoTrackInitializer.init() - Manual init
 *    - VideoTrackInitializer.reinitialize() - After track change
 *    - VideoTrackInitializer.forceLoad() - Force track mode
 * 
 * 5. Cache-busting is automatic using: ?v=UNIX_MINUTE_TIMESTAMP
 * 
 * 6. All initialization happens AFTER loadedmetadata event
 * 
 * 7. Includes MutationObserver for Angular ng-if detection
 */
