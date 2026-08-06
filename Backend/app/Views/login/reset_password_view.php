<!DOCTYPE html>
<html lang="<?php $locale ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
                                <img src="<?php echo base_url() ?>/public/img/login_logo.png" class="img-sqaure img-thumbnail" />

                            </div>
                        </div>
                        <h1>Reset Password</h1>
                    </div>


                    <!-- <?php if (session()->get('success')) : ?>
                            <div class="alert alert-success" role="alert">
                                <?= session()->get('success') ?>
                            </div>
                        <?php endif; ?>
                        <?php if (session()->get('error')) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= session()->get('error') ?>
                            </div>
                        <?php endif; ?> -->
                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger" style="font-size:15px"><?= $error; ?></div>
                    <?php else : ?>
                        <form method="POST"><?= csrf_field() ?>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="New Password" value="">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password_confirm" placeholder="Confirm Password" value="">
                            </div>
                            <div>
                                <?php if (isset($validation)) : ?>
                                    <div>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $validation->listErrors() ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-sm btn-warning form-control">Reset Password</button>
                            </div>
                            <a href="<?php echo base_url('/'); ?>">Back</a>
                            <div class="separator">
                                <div>
                                    <p> Copyirght &COPY; DoChek <?php echo date('Y'); ?>. All rights reserved.</p>
                                </div>
                                <div>
                                    <p><a href="<?php echo base_url('ang/privacy') ?>" target="_blank">Privacy Information</a>| &nbsp; <a href="<?php echo base_url('ang/terms') ?>" target="_blank">Terms of Use</a></p>
                                </div>
                            </div>
                        </form>

                    <?php endif ?>



                </section>
            </div>
        </div>
    </div>
</body>

</html>