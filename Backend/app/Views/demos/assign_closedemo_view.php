<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <!-- <link href="<?php echo base_url() ?>/public/newtheme/css/stylesheets.css" rel="stylesheet" type="text/css" /> -->
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
                                <h6 class="modal-title">Email & Passcode already exist. Please use unique passcode.</h6>
                            </div>
                            <div class="modal-footer">
                                <button id="success_close" type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready(function() {
        $("#success_close").click(function() {
            $("#success_model").hide();
            window.location.href = '<?php echo base_url("demos/mycart") ?>';
        });

    });
    //setTimeout(function () { window.location.reload(); }, 5*60*1000);
    // just show current time stamp to see time of last refresh.
    //document.write(new Date());
</script>