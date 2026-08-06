<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <title>DOCHEK</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>public/Landing/images/favicon.ico">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/Landing/css/bootstrap.min.css" type="text/css">
    <!--Material Icon -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/Landing/css/materialdesignicons.min.css" />
    <!-- Custom  sCss -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/Landing/css/style.css" />

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

    <link rel="stylesheet" href="<?= base_url('assets/assets/ang_reset/css/styles.css') ?>">
</head>

<body>
    <header class="header">
        <div class="header-left">
            <a class="header-logo" href="<?php echo base_url('ang/') ?>">
                <img src="<?= base_url('assets/assets/ang_reset/assets/images/logos/dark-logo.svg') ?>" alt="DOCHEK" class="logo-img">
            </a>
        </div>

        <nav class="header-center">
            <a href="<?php echo base_url('ang/about') ?>">About Us</a>
            <a href="<?php echo base_url('ang/features') ?>">Features</a>
            <a href="<?php echo base_url('ang/catalog') ?>">Course Catalog</a>
            <a href="<?php echo base_url('ang/blogs') ?>">Blogs</a>
            <a href="<?php echo base_url('ang/contact') ?>">Contact</a>
        </nav>

        <div class="header-right">
            <a href="<?php echo base_url('ang/login') ?>"><button class="login-btn">Login</button></a>
        </div>
    </header>

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
            <?php if (session()->get('success')): ?>
                <div class="alert alert-success" role="alert" style="font-size:15px">
                    <?= session()->get('success') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->get('error')): ?>
                <div class="alert alert-danger" role="alert" style="font-size:15px">
                    <?= session()->get('error') ?>
                </div>
            <?php endif; ?>






            <?php if ($landing_type == 1) { ?>
                <?php if ($username != 'notvalid') { ?>
                    <div class="form-card">
                        <form autocomplete="off" action="<?php echo base_url('quickaccess/checkAccess'); ?>"
                            method="POST"><?= csrf_field() ?>
                            <div class="auth-brand">
                                <a href="<?php echo base_url(''); ?>" class="logo logo-light text-center">
                                    <span class="logo-lg">
                                        <h3>Quick Access</h3>
                                        <br>
                                    </span>
                                </a>
                            </div>

                            <div class="mb-3">
                                <input type="hidden" class="form-control" autocomplete="off" name="username"
                                    id="username" placeholder="Username" value="<?php echo $username; ?>">
                                <input type="hidden" name="demoid" value="<?php echo $demoid; ?>">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" autocomplete="off" name="password"
                                    id="password" placeholder="Password" value="" required>
                            </div>
                            <div class="mb-3">
                                <button type="submit"
                                    class="btn btn-default btn-primary btn-sm">Login</button>
                            </div>
                        </form>
                    <?php } ?>
                    <?php if (isset($quickvalidation)): ?>
                        <div>
                            <div class="alert alert-danger" role="alert">
                                <?= $quickvalidation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php } elseif ($landing_type == 2) { ?>
                    <div class="form-card">
                        <form autocomplete="off" class="form"
                            action="<?php echo base_url('forgot_password'); ?>" method="POST"><?= csrf_field() ?>
                            <div class="auth-brand">
                                <a href="<?php echo base_url(''); ?>" class="logo logo-light text-center">
                                    <span class="logo-lg">
                                        <h3>Forgot Password</h3>
                                        <br>
                                    </span>
                                </a>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="email"
                                    placeholder="Enter your email" value="<?= set_value('email') ?>">
                            </div>
                            <div>
                                <?php if (isset($validation)): ?>
                                    <div>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $validation->listErrors() ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="form-control btn btn-sm btn-warning">Request
                                    Password Reset</button>
                            </div>
                            <div class="mb-3">
                                <a href="<?php echo base_url('/'); ?>" class="text-muted float-end"><small>Back
                                        to Sign in</small></a>
                            </div>

                        </form>
                    <?php } elseif ($landing_type == 3) { ?>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" style="font-size:15px"><?= $error; ?></div>
                        <?php else: ?>
                            <div class="form-card">
                                <h2 class="form-title">Reset Your Password</h2>

                                <form autocomplete="off" method="POST"><?= csrf_field() ?>

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
                                        <!-- Per-field hint shown below the New Password input only -->
                                        <p class="helper-text">
                                            <span class="chk">&#10003;</span> Min 8 chars
                                            &nbsp;|&nbsp;
                                            <span class="chk">&#10003;</span> Letters, numbers &amp; special chars
                                        </p>
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
                                    </div>
                                    <div>
                                        <?php if (isset($validation)): ?>
                                            <div>
                                                <div class="alert alert-danger" role="alert">
                                                    <?= $validation->listErrors() ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" id="resetBtn" disabled>
                                            Reset Password
                                        </button>
                                        <a href="<?php echo base_url('/ang'); ?>"> <button type="button" class="btn btn-outline" id="backBtn">
                                                Back to Login
                                            </button></a>
                                    </div>
                                </form>
                            <?php endif ?>

                        <?php } else { ?>
                            <?php if (session()->get('login_source') == 'ci') { ?>
                                <div class="form-card">
                                    <h2 class="form-title">Welcome to DOCHEK</h2>
                                    <form autocomplete="off"
                                        action="<?php echo base_url('Landing_dochek/login_register'); ?>" method="POST"><?= csrf_field() ?>
                                    <?php } else { ?>
                                        <h2 class="form-title">Welcome to DOCHEK</h2>
                                        <form autocomplete="off" action="<?php echo base_url('landing/login_register'); ?>"
                                            method="POST"><?= csrf_field() ?>
                                        <?php } ?>
                                        <?= csrf_field() ?>
                                        <div class="form-group">
                                            <label for="Username" class="form-label">Username</label>
                                            <div class="input-wrapper">
                                                <input class="form-input" id="username" autocomplete="off" name="username"
                                                    required="" placeholder="Enter your email"
                                                    value="<?= set_value('username') ?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="Password" class="form-label">Password</label>
                                            <div class="input-wrapper">
                                                <input type="password" id="password" autocomplete="off" name="password"
                                                    class="form-input" placeholder="Enter your password">
                                            </div>
                                        </div>
                                        <?php if (isset($validation)): ?>
                                            <div>
                                                <div class="alert alert-danger" role="alert">
                                                    <?= $validation->listErrors() ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <a href="<?php echo base_url('forgot_password'); ?>"
                                            class="text-primary f-w-600 text-decoration-none f-s-14"><small>Forgot your password?</small></a>
                                        <div class="text-center d-grid">
                                            <button class="btn btn-outline" type="submit"> Sign In </button>
                                        </div>

                                        </form>
                                    <?php } ?>
                                </div> <!-- end card-body -->
                            </div> <!-- end col -->
                    </div>


    </main>

    <script src="<?= base_url('assets/assets/ang_reset/js/script.js') ?>"></script>


</body>

</html>