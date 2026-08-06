<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <title>Reset Password - DOCHEK</title>
    <script>
        (function() {
            var root = document.documentElement;

            function getQueryParam(name) {
                var query = window.location.search || '';
                if (!query || query.length < 2) {
                    return null;
                }

                var parts = query.substring(1).split('&');
                for (var i = 0; i < parts.length; i++) {
                    var pair = parts[i].split('=');
                    if (decodeURIComponent(pair[0] || '') === name) {
                        return decodeURIComponent((pair[1] || '').replace(/\+/g, ' '));
                    }
                }
                return null;
            }

            var forced = getQueryParam('theme');
            var mediaQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;
            var prefersDark = mediaQuery ? mediaQuery.matches : false;

            function safeGet(keys) {
                try {
                    for (var i = 0; i < keys.length; i++) {
                        var v = localStorage.getItem(keys[i]);
                        if (v !== null && v !== '') {
                            return v;
                        }
                    }
                } catch (e) {
                    // Some file:// or strict privacy modes can block localStorage.
                }
                return '';
            }

            var savedRaw = safeGet(['theme', 'color-theme', 'app-theme', 'isDarkMode']);
            var saved = String(savedRaw).toLowerCase();

            var forcedDark = forced === 'dark';
            var forcedLight = forced === 'light';
            var savedDark = saved === 'dark' || saved === 'true' || saved === '1' || saved === 'on' || saved === 'enabled';
            var savedLight = saved === 'light' || saved === 'false' || saved === '0' || saved === 'off' || saved === 'disabled';

            var shouldUseDark = forcedDark || savedDark || (!forcedLight && !savedLight && prefersDark);

            if (shouldUseDark) {
                root.classList.add('theme-dark');
                root.classList.remove('theme-light');
            } else {
                root.classList.remove('theme-dark');
                root.classList.add('theme-light');
            }

            if (mediaQuery && !forcedDark && !forcedLight && !savedDark && !savedLight) {
                var onChange = function(e) {
                    if (e.matches) {
                        root.classList.add('theme-dark');
                        root.classList.remove('theme-light');
                    } else {
                        root.classList.remove('theme-dark');
                        root.classList.add('theme-light');
                    }
                };

                if (typeof mediaQuery.addEventListener === 'function') {
                    mediaQuery.addEventListener('change', onChange);
                } else if (typeof mediaQuery.addListener === 'function') {
                    mediaQuery.addListener(onChange);
                }
            }
        }());
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/assets/ang_reset/css/styles.css'); ?>">
    <!-- Cloudflare Turnstile — explicit render; widget is rendered by js/script.js -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit" async></script>
</head>

<body>
    <header class="header" id="mainHeader">
        <div class="header-left">
            <a class="header-logo" href="#">
                <img src="<?php echo base_url('assets/assets/ang_reset/assets/images/logos/dark-logo.svg'); ?>" alt="DOCHEK" class="logo-img">
            </a>
        </div>

        <nav class="header-center" aria-label="Primary navigation">
            <a href="<?php echo base_url('ang/about') ?>">About Us</a>
            <a href="<?php echo base_url('ang/features') ?>">Features</a>
            <a href="<?php echo base_url('ang/catalog') ?>">Course Catalog</a>
            <a href="<?php echo base_url('ang/certifications') ?>">Certifications</a>
        </nav>

        <div class="header-right">
            <a href="<?php echo base_url('ang/login') ?>"><button class="login-btn">Login</button>
                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Open navigation menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="22" height="22" aria-hidden="true">
                        <line x1="3" y1="6" x2="21" y2="6" stroke-linecap="round" />
                        <line x1="3" y1="12" x2="21" y2="12" stroke-linecap="round" />
                        <line x1="3" y1="18" x2="21" y2="18" stroke-linecap="round" />
                    </svg>
                </button></a>
        </div>
    </header>

    <!-- Mobile nav backdrop -->
    <div class="mobile-nav-backdrop" id="mobileNavBackdrop"></div>

    <!-- Mobile nav slide-in panel -->
    <nav class="mobile-nav-panel" id="mobileNavPanel" aria-label="Mobile navigation">
        <div class="mobile-nav-header-panel">
            <a class="header-logo" href="#">
                <img src="<?php echo base_url('assets/assets/ang_reset/assets/images/logos/dark-logo.svg'); ?>" alt="DOCHEK" class="logo-img">
            </a>
            <button class="mobile-close-btn" id="mobileCloseBtn" aria-label="Close navigation">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="22" height="22" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18" stroke-linecap="round" />
                    <line x1="6" y1="6" x2="18" y2="18" stroke-linecap="round" />
                </svg>
            </button>
        </div>
        <ul class="mobile-nav-list">
            <li><a href="<?php echo base_url('ang/about') ?>">About Us</a></li>
            <li><a href="<?php echo base_url('ang/features') ?>">Features</a></li>
            <li><a href="<?php echo base_url('ang/catalog') ?>">Course Catalog</a></li>
            <li><a href="<?php echo base_url('ang/certifications') ?>">Certifications</a></li>
        </ul>
        <a href="<?php echo base_url('ang/login') ?>"> <button class="btn btn-primary mobile-login-full-btn">Login</button></a>
    </nav>

    <main class="main-container">
        <div class="left-section">
            <div class="illustration-frame">
                <img
                    class="security-image"
                    src="<?= base_url('assets/assets/ang_reset/assets/images/backgrounds/login-bg.svg') ?>"
                    alt="Security illustration – lock and key" />
            </div>
        </div>

        <div class="right-section">
            <?php if (isset($error)): ?>
                <div class="status-card status-card--error" role="alert" aria-live="polite">
                    <p class="status-card__message status-card__message--lead"><?= $error; ?></p>
                </div>
            <?php else: ?>

                <div class="form-card">
                    <h2 class="form-title" id="formTitle">Reset Your Password</h2>

                    <?php if (session()->get('error')): ?>
                        <div class="status-card status-card--error" role="alert" aria-live="polite">
                            <p class="status-card__message status-card__message--lead"><?= session()->get('error') ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($validation)): ?>
                        <div class="status-card status-card--error" role="alert" aria-live="polite">
                            <div class="status-card__message status-card__message--lead"><?= $validation->listErrors() ?></div>
                        </div>
                    <?php endif; ?>

                    <form id="resetPasswordForm" novalidate method="POST"><?= csrf_field() ?>

                        <!-- New Password -->
                        <div class="form-group">
                            <label for="newPassword" class="form-label">New Password</label>
                            <div class="input-wrapper">
                                <input
                                    name="password"
                                    type="password"
                                    id="newPassword"
                                    class="form-input"
                                    placeholder="Enter new password"
                                    autocomplete="new-password" />
                                <button class="eye-btn" id="toggleNew" type="button" aria-label="Show new password">
                                    <svg class="eye-svg eye-off" id="eyeOffNew" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M3 3l18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        <path d="M9.9 4.2A10.4 10.4 0 0 1 12 4c5.4 0 9 5 10 8a14.6 14.6 0 0 1-3.1 4.8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M6.6 6.6A14.2 14.2 0 0 0 2 12c1 3 4.6 8 10 8a10.7 10.7 0 0 0 4.6-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <svg class="eye-svg eye-on hidden" id="eyeOnNew" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                        <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2" />
                                    </svg>
                                </button>
                            </div>
                            <!-- Password Strength Checklist (mirrors Sign Up page) -->
                            <div class="password-checklist" id="pwChecklist" aria-label="Password requirements"></div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <div class="input-wrapper">
                                <input
                                    name="password_confirm"
                                    type="password"
                                    id="confirmPassword"
                                    class="form-input"
                                    placeholder="Re-enter password"
                                    autocomplete="new-password" />
                                <button class="eye-btn" id="toggleConfirm" type="button" aria-label="Show confirm password">
                                    <svg class="eye-svg eye-off" id="eyeOffConfirm" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M3 3l18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                        <path d="M9.9 4.2A10.4 10.4 0 0 1 12 4c5.4 0 9 5 10 8a14.6 14.6 0 0 1-3.1 4.8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M6.6 6.6A14.2 14.2 0 0 0 2 12c1 3 4.6 8 10 8a10.7 10.7 0 0 0 4.6-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <svg class="eye-svg eye-on hidden" id="eyeOnConfirm" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                        <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2" />
                                    </svg>
                                </button>
                            </div>
                            <p id="confirmMismatchMsg" class="confirm-mismatch-msg is-hidden" role="alert" aria-live="polite">Passwords do not match.</p>
                        </div>

                        <!-- Cloudflare Turnstile CAPTCHA -->
                        <div class="turnstile-wrapper">
                            <div id="turnstileContainer"></div>
                            <p id="captchaError" class="captcha-error-msg is-hidden" role="alert" aria-live="polite"></p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" id="resetBtn" disabled>
                                Reset Password
                            </button>
                            <button type="button" class="btn btn-outline" id="backBtn">
                                Back to Login
                            </button>
                        </div>

                    </form>

                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- ── Enterprise Footer ────────────────────────────────── -->
    <footer class="site-footer">
        <div class="footer-wrapper">
            <div class="footer-inner">

                <div class="footer-main">

                    <!-- Brand -->
                    <div class="footer-brand">
                        <div class="footer-brand-lockup">
                            <img src="<?php echo base_url('assets/assets/ang_reset/assets/images/logos/dark-logo.svg') ?>" alt="DOCHEK" class="logo-img footer-brand-logo">
                        </div>
                        <p class="footer-brand-desc">
                            One platform. All your learning needs.<br>
                            Deliver knowledge, track progress,<br>
                            and empower success.
                        </p>
                        <div class="footer-socials">
                            <!-- LinkedIn -->
                            <a href="https://www.linkedin.com/company/touchstonelc" target="_blank" class="social-btn" aria-label="LinkedIn">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="15" height="15" aria-hidden="true">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                            </a>
                            <!-- Instagram -->
                            <a href="https://www.instagram.com/touchstonelc/" target="_blank" class="social-btn" aria-label="Instagram">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="15" height="15" aria-hidden="true">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                </svg>
                            </a>
                            <!-- YouTube -->
                            <a href="https://www.youtube.com/@touchstonelc" target="_blank" class="social-btn" aria-label="YouTube">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="15" height="15" aria-hidden="true">
                                    <path d="M21.8 8.001s-.198-1.398-.806-2.013c-.771-.807-1.635-.81-2.035-.858C15.076 5 12 5 12 5s-3.076 0-6.959.13c-.4.048-1.264.051-2.035.858C2.398 6.603 2.2 8.001 2.2 8.001S2 9.642 2 11.283v1.433c0 1.641.2 3.282.2 3.282s.198 1.398.806 2.013c.771.807 1.784.781 2.236.867 1.627.155 6.758.122 6.758.122s3.079-.005 6.963-.134c.4-.048 1.264-.051 2.035-.858.608-.615.806-2.013.806-2.013s.2-1.641.2-3.282v-1.433c0-1.641-.2-3.282-.2-3.282ZM9.957 14.585V9.417l5.427 2.584-5.427 2.584Z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="footer-col">
                        <h6 class="footer-col-heading">Quick Links</h6>
                        <a class="footer-link" href="<?php echo base_url('ang/about') ?>"><span class="material-icons fli">info</span>About Us</a>
                        <a class="footer-link" href="<?php echo base_url('ang/blogs') ?>"><span class="material-icons fli">article</span>Blogs</a>
                        <a class="footer-link" href="<?php echo base_url('ang/catalog') ?>"><span class="material-icons fli">library_books</span>Course Catalog</a>
                        <a class="footer-link" href="<?php echo base_url('ang/contact') ?>"><span class="material-icons fli">mail</span>Contact Us</a>
                    </div>

                    <!-- Platform -->
                    <div class="footer-col">
                        <h6 class="footer-col-heading">Platform</h6>
                        <a class="footer-link" href="<?php echo base_url('ang/login') ?>"><span class="material-icons fli">login</span>Member Login</a>
                        <a class="footer-link" href="#"><span class="material-icons fli">event</span>Book a Demo</a>
                        <a class="footer-link" href="<?php echo base_url('ang/features') ?>"><span class="material-icons fli">auto_awesome</span>Features</a>
                        <a class="footer-link" href="<?php echo base_url('ang/certifications') ?>"><span class="material-icons fli">verified</span>Certifications</a>
                    </div>

                    <!-- Legal -->
                    <div class="footer-col">
                        <h6 class="footer-col-heading">Legal</h6>
                        <a class="footer-link" href="<?php echo base_url('ang/privacy') ?>"><span class="material-icons fli">privacy_tip</span>Privacy Policy</a>
                        <a class="footer-link" href="<?php echo base_url('ang/terms') ?>"><span class="material-icons fli">gavel</span>Terms of Service</a>
                    </div>

                    <!-- Contact cards -->
                    <div class="footer-contact-col">
                        <div class="contact-card">
                            <div class="contact-card-icon">
                                <span class="material-icons">mail_outline</span>
                            </div>
                            <div class="contact-card-body">
                                <a href="mailto:info@dochek.com" class="contact-card-value">info@dochek.com</a>
                                <span class="contact-card-sub">Email us anytime</span>
                            </div>
                        </div>
                        <div class="contact-card">
                            <div class="contact-card-icon">
                                <span class="material-icons">phone_in_talk</span>
                            </div>
                            <div class="contact-card-body">
                                <a href="tel:+14042669368" class="contact-card-value">+1 (404) 266-9368</a>
                                <span class="contact-card-sub">Mon – Fri, 9AM – 6PM EST</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Bottom strip -->
                <div class="footer-strip">
                    <div class="footer-strip-left">
                        <span class="footer-strip-copy">© 2026 DOCHEK. All rights reserved.</span>
                        <span class="footer-strip-pipe">|</span>
                        <a href="https://www.touchstonelc.com/" target="_blank" class="footer-strip-powered">Touchstone Product</a>
                    </div>
                    <div class="footer-strip-right">
                        <a class="footer-strip-nav" href="<?php echo base_url('ang/privacy'); ?>">Privacy</a>
                        <a class="footer-strip-nav" href="<?php echo base_url('ang/terms'); ?>">Terms</a>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <script src="<?php echo base_url('assets/assets/ang_reset/js/password-rules.js'); ?>"></script>
    <script src="<?php echo base_url('assets/assets/ang_reset/js/script.js'); ?>"></script>

    <!-- Reset Password page only — blocks whitespace in the New Password /
         Confirm Password fields. Scoped to #newPassword and #confirmPassword
         so it never touches the Forgot Password form or js/script.js. -->
    <script>
        (function () {
            'use strict';

            function stripWhitespace(input) {
                var value = input.value;
                var cleaned = value.replace(/\s+/g, '');
                if (cleaned === value) {
                    return;
                }

                var caret = input.selectionStart;
                var removedBeforeCaret = 0;
                for (var i = 0; i < caret && i < value.length; i++) {
                    if (/\s/.test(value.charAt(i))) {
                        removedBeforeCaret++;
                    }
                }

                input.value = cleaned;

                var newCaret = Math.max(0, caret - removedBeforeCaret);
                if (typeof input.setSelectionRange === 'function') {
                    input.setSelectionRange(newCaret, newCaret);
                }

                // Re-fire "input" so the existing checklist/match/submit-gating
                // listeners in js/script.js react to the cleaned value.
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }

            function blockSpaces(input) {
                if (!input) {
                    return;
                }

                // Desktop keyboards: stop the space character before it lands.
                input.addEventListener('keydown', function (e) {
                    if (e.key === ' ' || e.code === 'Space' || e.keyCode === 32) {
                        e.preventDefault();
                    }
                });

                // Catches everything keydown can miss (paste, drag-drop,
                // autofill, IME, mobile virtual keyboards).
                input.addEventListener('input', function () {
                    stripWhitespace(input);
                });
            }

            blockSpaces(document.getElementById('newPassword'));
            blockSpaces(document.getElementById('confirmPassword'));
        }());
    </script>

    <!-- Mobile navigation script -->
    <script>
        (function() {
            var btn = document.getElementById('mobileMenuBtn');
            var closeBtn = document.getElementById('mobileCloseBtn');
            var backdrop = document.getElementById('mobileNavBackdrop');
            var panel = document.getElementById('mobileNavPanel');

            function openNav() {
                panel.classList.add('open');
                backdrop.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closeNav() {
                panel.classList.remove('open');
                backdrop.classList.remove('open');
                document.body.style.overflow = '';
            }

            if (btn) btn.addEventListener('click', openNav);
            if (closeBtn) closeBtn.addEventListener('click', closeNav);
            if (backdrop) backdrop.addEventListener('click', closeNav);
        }());
    </script>
</body>

</html>