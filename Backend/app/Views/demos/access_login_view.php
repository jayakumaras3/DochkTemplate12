<!DOCTYPE html>
<html lang="<?php $locale ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo lang('UI_Text.Title') ?></title>
    
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/jquery/jquery.min.js'></script>
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
    <script>
        $(document).ready(function() {

            $("#close_access_empty").click(function() {
                $("#access_empty_model").hide();
            });
            $("#ok_access_empty").click(function() {
                $("#access_empty_model").hide();
            });
            $("#close_access_check").click(function() {
                $("#access_check").hide();
            });
            $("#ok_access_check").click(function() {
                $("#access_check").hide();
            });
            $("#close_date_check").click(function() {
                $("#date_check").hide();
            });
            $("#ok_date_check").click(function() {
                $("#date_check").hide();
            });

        });
    </script>
</head>
<?php if (isset($comment)) {
    echo '<div id="date_check" class="modal-dialog">
            <div class="modal-content">                
                <div class="modal-header">
                    <h6 class="modal-title">' . $comment . '</h6>
                    <button id="close_date_check" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                   
                   </div>
            </div>
        </div>';
}
?>

<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">

                    <form method="POST"><?= csrf_field() ?>
                        <div class="input-group">
                            <input type="password" name="access_code" class="form-control" placeholder="Accesscode" />
                        </div>

                        <div>
                            <button type="submit" name="access_check" class="btn btn-info btn-sm col-md-4">Log In</button>
                        </div>



                    </form>
                </section>
            </div>
        </div>
    </div>
</body>

</html>