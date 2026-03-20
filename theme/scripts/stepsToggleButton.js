/**
 * STEPS TOGGLE BUTTON MODULE
 * Manages floating toggle button for enabling/disabling the steps panel
 * Features:
 * - Dynamically creates floating button when steps exist
 * - Manages visibility state of steps panel
 * - Positioned at bottom-right corner (above video controls)
 * - Prevents duplicate button creation
 * - Handles page lifecycle (load, change, reset)
 * - Tracks video time and updates active step
 * - Allows clicking step to jump to that time
 */

(function() {
    'use strict';

    window.StepsToggleButton = {
        isEnabled: false,
        buttonElement: null,
        stepsPanelElement: null,
        stepProgressElement: null,
        currentSteps: null,
        currentStepIndex: -1,
        videoElement: null,
        timeUpdateHandler: null,

        /**
         * Initialize steps toggle functionality
         * @param {Array} steps - Array of step objects from page settings
         */
        init: function(steps) {
            console.log('[StepsToggleButton] Initializing with steps:', steps);

            // Safety: remove any legacy floating button instance from older builds.
            const legacyButton = document.getElementById('stepsToggleButton');
            if (legacyButton && legacyButton.parentNode) {
                legacyButton.parentNode.removeChild(legacyButton);
            }

            // Reset state on page change
            this.isEnabled = false;

            if (!steps || steps.length === 0) {
                console.log('[StepsToggleButton] No steps provided, destroying');
                // No steps on this page - hide components
                this.destroy();
                return;
            }

            // Store steps reference
            this.currentSteps = steps;
            this.currentStepIndex = -1;
            console.log('[StepsToggleButton] Stored', steps.length, 'steps');

            // Create or show toggle button
            this.createToggleButton();

            // Ensure step panel exists
            this.ensureStepPanelExists();

            // Create top progress bar overlay
            this.ensureStepProgressOverlayExists();

            // Set up video tracking if video content
            this.setupVideoTracking();

            console.log('[StepsToggleButton] Initialization complete');
        },

        /**
         * Create the floating toggle button
         * Positioned at bottom-right, above video controls
         * 
         * NOTE: Button creation is now handled by integrated footer button (content.html)
         * This method is kept for backwards compatibility but no longer creates a DOM element
         */
        createToggleButton: function() {
            // Button is now managed by footerBarController and integrated into footer
            // No floating button creation needed - skip this entirely
            console.log('[StepsToggleButton] Button creation skipped - using integrated footer button');
        },

        /**
         * Ensure step panel HTML structure exists
         * Creates standalone panel if not present
         */
        ensureStepPanelExists: function() {
            var stepPanel = document.getElementById('stepsPanel');

            if (stepPanel && document.body.contains(stepPanel)) {
                this.stepsPanelElement = stepPanel;
                this.updateStepsContent(stepPanel);
                return;
            }

            stepPanel = document.createElement('div');
            stepPanel.id = 'stepsPanel';
            stepPanel.className = 'step-panel';
            stepPanel.setAttribute('role', 'region');
            stepPanel.setAttribute('aria-label', 'Procedure Steps');

            var header = document.createElement('div');
            header.className = 'step-panel-header';

            var headerTitle = document.createElement('h3');
            headerTitle.textContent = 'Procedure Steps';
            header.appendChild(headerTitle);

            var closeBtn = document.createElement('button');
            closeBtn.className = 'step-panel-close';
            closeBtn.setAttribute('aria-label', 'Close Steps Panel');
            closeBtn.innerHTML = '&times;';

            var self = this;
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                self.disablePanel();
            });

            header.appendChild(closeBtn);
            stepPanel.appendChild(header);

            var stepsContainer = document.createElement('div');
            stepsContainer.className = 'step-panel-content';
            stepPanel.appendChild(stepsContainer);

            document.body.appendChild(stepPanel);
            this.stepsPanelElement = stepPanel;

            this.updateStepsContent(stepPanel);
            console.log('[StepsToggleButton] Standalone steps panel created');
        },

        /**
         * Ensure top step progress overlay exists in video frame
         */
        ensureStepProgressOverlayExists: function() {
            if (!this.currentSteps || this.currentSteps.length === 0) {
                this.removeStepProgressOverlay();
                return;
            }

            var frame = document.querySelector('.shorts-video-frame') ||
                document.querySelector('.contentArea.video-mode .pageContent') ||
                document.querySelector('.pageContent');

            if (!frame) {
                return;
            }

            var overlay = frame.querySelector('.step-progress-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'step-progress-overlay';
                overlay.setAttribute('aria-label', 'Step progress');

                var segments = document.createElement('div');
                segments.className = 'step-progress-segments';
                overlay.appendChild(segments);

                var info = document.createElement('div');
                info.className = 'step-progress-info';

                var meta = document.createElement('div');
                meta.className = 'step-progress-meta';
                info.appendChild(meta);

                var title = document.createElement('div');
                title.className = 'step-progress-title';
                info.appendChild(title);

                overlay.appendChild(info);
                frame.appendChild(overlay);
            }

            this.stepProgressElement = overlay;
            this.renderStepProgressSegments();
        },

        /**
         * Render segment elements from current steps
         */
        renderStepProgressSegments: function() {
            if (!this.stepProgressElement || !this.currentSteps) {
                return;
            }

            var segmentsContainer = this.stepProgressElement.querySelector('.step-progress-segments');
            if (!segmentsContainer) {
                return;
            }

            segmentsContainer.innerHTML = '';
            for (var i = 0; i < this.currentSteps.length; i++) {
                var seg = document.createElement('span');
                seg.className = 'step-progress-segment upcoming';
                seg.setAttribute('data-step-index', i);
                segmentsContainer.appendChild(seg);
            }
        },

        /**
         * Compute current step index from video time
         */
        getStepIndexForTime: function(currentTime) {
            if (!this.currentSteps || this.currentSteps.length === 0) {
                return -1;
            }

            var index = 0;
            for (var i = 0; i < this.currentSteps.length; i++) {
                var step = this.currentSteps[i] || {};
                var next = this.currentSteps[i + 1] || null;
                var start = typeof step.time === 'number' ? step.time : parseFloat(step.time) || 0;
                var end = next ? ((typeof next.time === 'number' ? next.time : parseFloat(next.time)) || Number.MAX_SAFE_INTEGER) : Number.MAX_SAFE_INTEGER;

                if (currentTime >= start && currentTime < end) {
                    index = i;
                    break;
                }

                if (currentTime >= start) {
                    index = i;
                }
            }

            return index;
        },

        /**
         * Update top progress overlay and segment states
         */
        updateStepProgressByTime: function(currentTime) {
            if (!this.currentSteps || this.currentSteps.length === 0) {
                return;
            }

            if (!this.stepProgressElement || !document.body.contains(this.stepProgressElement)) {
                this.ensureStepProgressOverlayExists();
            }

            var nextIndex = this.getStepIndexForTime(currentTime);
            if (nextIndex === this.currentStepIndex) {
                return;
            }

            this.currentStepIndex = nextIndex;
            this.updateStepBar(nextIndex);
            this.updateStepInfo(nextIndex);
        },

        /**
         * Update segment states: completed / active / upcoming
         */
        updateStepBar: function(index) {
            if (!this.stepProgressElement) {
                return;
            }

            var segments = this.stepProgressElement.querySelectorAll('.step-progress-segment');
            for (var i = 0; i < segments.length; i++) {
                var seg = segments[i];
                seg.classList.remove('completed', 'active', 'upcoming');

                if (i < index) {
                    seg.classList.add('completed');
                } else if (i === index) {
                    seg.classList.add('active');
                } else {
                    seg.classList.add('upcoming');
                }
            }
        },

        /**
         * Update STEP X OF Y and current step title
         */
        updateStepInfo: function(index) {
            if (!this.stepProgressElement || !this.currentSteps || this.currentSteps.length === 0 || index < 0) {
                return;
            }

            var meta = this.stepProgressElement.querySelector('.step-progress-meta');
            var title = this.stepProgressElement.querySelector('.step-progress-title');
            var step = this.currentSteps[index] || {};

            if (meta) {
                meta.textContent = 'STEP ' + (index + 1) + ' OF ' + this.currentSteps.length;
            }
            if (title) {
                title.textContent = step.title || ('Step ' + (index + 1));
            }
        },

        /**
         * Remove top step progress overlay
         */
        removeStepProgressOverlay: function() {
            if (this.stepProgressElement && this.stepProgressElement.parentNode) {
                this.stepProgressElement.parentNode.removeChild(this.stepProgressElement);
            }
            this.stepProgressElement = null;
            this.currentStepIndex = -1;
        },

        /**
         * Update step panel content with current steps
         * @param {HTMLElement} stepPanel - The step panel element
         */
        updateStepsContent: function(stepPanel) {
            if (!this.currentSteps || this.currentSteps.length === 0) {
                return;
            }

            const container = stepPanel.querySelector('.step-panel-content');
            if (!container) return;

            // Clear existing content
            container.innerHTML = '';

            const self = this;

            // Build step items
            this.currentSteps.forEach(function(step) {
                const stepItem = document.createElement('div');
                stepItem.className = 'step-item';
                stepItem.dataset.time = step.time || 0;
                stepItem.style.cssText = `
                    display: flex !important;
                    gap: 12px !important;
                    padding: 12px 16px !important;
                    cursor: pointer !important;
                    border-left: 3px solid transparent !important;
                    transition: all 0.2s ease !important;
                `;

                const stepNumber = document.createElement('div');
                stepNumber.className = 'step-number';
                stepNumber.textContent = step.step || '';
                stepNumber.style.cssText = `
                    min-width: 36px !important;
                    width: 36px !important;
                    height: 36px !important;
                    border-radius: 50% !important;
                    background: rgba(25, 118, 210, 0.15) !important;
                    color: #1976d2 !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    font-weight: 600 !important;
                    font-size: 14px !important;
                    flex-shrink: 0 !important;
                `;

                const stepContent = document.createElement('div');
                stepContent.className = 'step-content';
                stepContent.style.cssText = `
                    flex: 1 !important;
                    min-width: 0 !important;
                `;

                const stepTitle = document.createElement('div');
                stepTitle.className = 'step-title';
                stepTitle.textContent = step.title || '';
                stepTitle.style.cssText = `
                    font-size: 13px !important;
                    font-weight: 600 !important;
                    color: #212121 !important;
                    margin-bottom: 4px !important;
                `;

                const stepDesc = document.createElement('div');
                stepDesc.className = 'step-description';
                stepDesc.textContent = step.description || '';
                stepDesc.style.cssText = `
                    font-size: 12px !important;
                    color: #666 !important;
                    line-height: 1.4 !important;
                    word-break: break-word !important;
                `;

                stepContent.appendChild(stepTitle);
                stepContent.appendChild(stepDesc);

                stepItem.appendChild(stepNumber);
                stepItem.appendChild(stepContent);

                // Add hover effects
                stepItem.addEventListener('mouseenter', function() {
                    this.style.background = 'rgba(25, 118, 210, 0.08)';
                    this.style.borderLeftColor = 'rgba(25, 118, 210, 0.3)';
                });
                stepItem.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        this.style.background = 'transparent';
                        this.style.borderLeftColor = 'transparent';
                    }
                });

                // Track current step on click - seek video to step time
                stepItem.addEventListener('click', function() {
                    const video = self.videoElement || document.getElementById('vidArea');
                    if (video && step.time !== undefined) {
                        console.log('[StepsToggleButton] Seeking video to', step.time, 'seconds');
                        video.currentTime = step.time;
                        video.play(); // Resume playback at step time
                    }
                });

                container.appendChild(stepItem);
            });

            console.log('[StepsToggleButton] Steps content updated');
        },

        /**
         * Set up video time tracking
         * Attaches listener to video element to update active step
         */
        setupVideoTracking: function() {
            const self = this;

            // Defer video tracking setup to ensure video element is available
            const setupTrackingWithRetry = function(attempt) {
                const video = document.getElementById('vidArea');

                if (video) {
                    self.videoElement = video;

                    // Remove old handler if exists
                    if (self.timeUpdateHandler) {
                        video.removeEventListener('timeupdate', self.timeUpdateHandler);
                    }

                    // Create new time update handler
                    self.timeUpdateHandler = function() {
                        self.updateStepProgressByTime(video.currentTime);
                        self.updateActiveStep(video.currentTime);
                    };

                    // Add event listener for video time updates
                    video.addEventListener('timeupdate', self.timeUpdateHandler);

                    // Initial render
                    self.updateStepProgressByTime(video.currentTime || 0);

                    console.log('[StepsToggleButton] Video tracking enabled');
                } else if (attempt < 5) {
                    // Retry if video not found (max 5 attempts with 200ms delay)
                    setTimeout(function() {
                        setupTrackingWithRetry(attempt + 1);
                    }, 200);
                } else {
                    console.warn('[StepsToggleButton] Could not locate video element for step tracking');
                }
            };

            // Start with first attempt
            setupTrackingWithRetry(0);
        },

        /**
         * Toggle the steps panel visibility
         * Updates button state and panel visibility
         */
        togglePanel: function() {
            if (this.isEnabled) {
                this.disablePanel();
            } else {
                this.enablePanel();
            }
        },

        /**
         * Enable/show the steps panel
         */
        enablePanel: function() {
            this.ensureStepPanelExists();
            this.isEnabled = true;

            if (this.stepsPanelElement) {
                this.stepsPanelElement.classList.add('open');
                this.stepsPanelElement.style.display = 'flex';
                this.stepsPanelElement.style.visibility = 'visible';
                this.stepsPanelElement.style.opacity = '1';
                this.stepsPanelElement.style.pointerEvents = 'auto';
                this.stepsPanelElement.style.zIndex = '99998';
            }

            console.log('[StepsToggleButton] Panel enabled');
        },

        /**
         * Disable/hide the steps panel
         */
        disablePanel: function() {
            this.isEnabled = false;

            if (this.stepsPanelElement) {
                this.stepsPanelElement.classList.remove('open');
                this.stepsPanelElement.style.opacity = '0';
                this.stepsPanelElement.style.pointerEvents = 'none';
            }

            console.log('[StepsToggleButton] Panel disabled');
        },

        /**
         * Destroy/hide toggle button and panel
         * Called when no steps exist on page
         */
        destroy: function() {
            const legacyButton = document.getElementById('stepsToggleButton');
            if (legacyButton && legacyButton.parentNode) {
                legacyButton.parentNode.removeChild(legacyButton);
            }

            if (this.buttonElement) {
                this.buttonElement.style.display = 'none';
            }

            if (this.stepsPanelElement) {
                this.stepsPanelElement.style.display = 'none';
            }

            this.removeStepProgressOverlay();

            this.isEnabled = false;

            // Remove video tracking
            if (this.videoElement && this.timeUpdateHandler) {
                this.videoElement.removeEventListener('timeupdate', this.timeUpdateHandler);
            }

            console.log('[StepsToggleButton] Destroyed/Hidden');
        },

        /**
         * Reset state for new page
         */
        reset: function() {
            const legacyButton = document.getElementById('stepsToggleButton');
            if (legacyButton && legacyButton.parentNode) {
                legacyButton.parentNode.removeChild(legacyButton);
            }

            this.isEnabled = false;
            this.currentSteps = null;
            this.currentStepIndex = -1;

            if (this.stepsPanelElement) {
                this.stepsPanelElement.classList.remove('open');
            }

            if (this.buttonElement) {
                this.buttonElement.setAttribute('aria-pressed', 'false');
                this.buttonElement.classList.remove('active');
            }

            // Remove old video tracking
            if (this.videoElement && this.timeUpdateHandler) {
                this.videoElement.removeEventListener('timeupdate', this.timeUpdateHandler);
                this.videoElement = null;
                this.timeUpdateHandler = null;
            }

            this.removeStepProgressOverlay();
        },

        /**
         * Track video time and update active step
         * Called during video playback via timeupdate event
         * @param {number} currentTime - Current video time in seconds
         */
        updateActiveStep: function(currentTime) {
            if (!this.currentSteps) {
                return;
            }

            const stepPanel = document.getElementById('stepsPanel');
            if (!stepPanel) return;

            const stepItems = stepPanel.querySelectorAll('.step-item');

            stepItems.forEach(function(item, index) {
                const stepTime = parseFloat(item.dataset.time);
                
                // Determine the duration of this step
                // Use next step's time or add 10 seconds as default
                const nextIndex = index + 1;
                let stepDuration = 10; // Default duration
                
                if (nextIndex < stepItems.length) {
                    const nextTime = parseFloat(stepItems[nextIndex].dataset.time);
                    stepDuration = nextTime - stepTime;
                } else {
                    // Last step - use arbitrary large duration
                    stepDuration = 3600; // 1 hour
                }

                // Mark step as active if within its time range
                if (currentTime >= stepTime && currentTime < (stepTime + stepDuration)) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }
    };

    // Initialize when document is ready
    console.log('[StepsToggleButton] Module script loaded');
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            console.log('[StepsToggleButton] DOMContentLoaded fired, module ready');
            window.StepsToggleButtonReady = true;
        });
    } else {
        console.log('[StepsToggleButton] Document already loaded, module ready');
        window.StepsToggleButtonReady = true;
    }
})();
