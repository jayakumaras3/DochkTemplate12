<!DOCTYPE html>
<html lang="<?php $locale ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="newtheme/image/ico" href="<?php echo base_url(); ?>/public/img/favicon.ico" />

    <title><?php echo lang('UI_Text.Title') ?></title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo base_url(); ?>/public/css/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <div class="head">
                        <div class="user">
                            <div class="info user-change">
                                <img src="public/img/login_logo.png" class="img-sqaure img-thumbnail" />
                            </div>
                        </div>
                    </div>
                    <br />


                    <?php if (session()->get('success')) : ?>
                        <div class="alert alert-success" role="alert" style="font-size:15px">
                            <?= session()->get('success') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->get('error')) : ?>
                        <div class="alert alert-danger" role="alert" style="font-size:15px">
                            <?= session()->get('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($username != 'notvalid') { ?>
                        <form action="<?php echo base_url('quickaccess/checkAccess'); ?>" method="POST"><?= csrf_field() ?>
                            <h1>Quick Access</h1>
                            <div>
                                <input type="hidden" class="form-control" name="username" placeholder="Username" value="<?php echo $username; ?>">
                            </div>
                            <div>
                                <input type="password" class="form-control" name="password" placeholder="Password" value="">
                            </div>
                            <div>

                            </div>
                            <div>
                                <button type="submit" class="btn btn-default btn-primary btn-sm"><?php echo lang('Buttons.login') ?></button>
                            </div>
                        </form>
                    <?php } ?>
                    <?php if (isset($validation)) : ?>
                        <div>
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="separator">
                        <div>
                            <p> Copyirght &COPY; DoChek <?php echo date('Y'); ?>. All rights reserved.</p>
                        </div>
                        <div>
                            <p><a href="<?php echo base_url(); ?>/privacy" target="_blank">Privacy Information</a>| &nbsp; <a href="<?php echo base_url(); ?>/privacy/term" target="_blank">Terms of Use</a></p>
                        </div>
                    </div>

                </section>
            </div>
        </div>
    </div>
</body>

</html>