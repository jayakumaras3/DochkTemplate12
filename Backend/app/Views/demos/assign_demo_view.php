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
    <link href="<?php echo base_url() ?>/public/newtheme/css/stylesheets.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo base_url(); ?>/public/css/build/css/custom.min.css" rel="stylesheet">

    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/bootstrap/bootstrap.min.js'></script>

    <!--script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script-->
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/uniform/jquery.uniform.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/fancybox/jquery.fancybox.pack.js'></script>

    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/datatables/jquery.dataTables.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/knob/jquery.knob.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/sparkline/jquery.sparkline.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/flot/jquery.flot.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/flot/jquery.flot.resize.js'></script>

    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/actions.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/charts.js'></script>




    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/uniform/jquery.uniform.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/flot/jquery.flot.pie.js'></script>


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


    <div class="container" style="padding: 40px;">

        <div class="row">
            <div class="col-md-12">
                <div class="clearfix">
                    <div class="pull-right tableTools-container"></div>
                </div>
                <div class="x_panel">

                    <?php $Link = base_url() . '/demos?Yusdd=' . $userid . '&jdick18=' . base64_encode($keyval); ?>

                    <div class="col-md-12">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Link : <?php echo $Link . "<br><br>" . 'Password : ' . $keyval; ?></h3>
                            </div>
                            <div class="modal-footer">
                                <button id="success_close" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $("#success_close").click(function() {
                                $("#success_model").hide();
                                window.location.href = "<?php echo base_url('demos/mycart') ?>";
                            });

                        });
                        //setTimeout(function () { window.location.reload(); }, 5*60*1000);
                        // just show current time stamp to see time of last refresh.
                        //document.write(new Date());
                    </script>
                    </section>
                </div>
            </div>
        </div>
    </div>
</body>

</html>