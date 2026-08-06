<html lang="en">

<head>
    <title>DoChek</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="icon" type="newtheme/image/ico" href="<?php echo base_url(); ?>/public/img/favicon.ico"/>

    <!-- <link href="<?php echo base_url(); ?>/public/newtheme/css/stylesheets.css" rel="stylesheet" type="text/css" />  -->

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


<body class="login">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="clearfix">
                    <div class="pull-right tableTools-container"></div>
                </div>
                <div id="danger_model" class="modal-dialog">
                    <div class="x_panel">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Please enter the App Username !</h6>
                            </div>
                            <div class="modal-footer">
                                <button id="ok_access_empty" type="button" class="btn btn-warning btn-clean" data-dismiss="modal">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>