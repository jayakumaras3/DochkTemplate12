<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="newtheme/image/ico" href="<?php echo base_url(); ?>/public/img/favicon.ico" />
    <title><?php echo lang('UI_Text.Title') ?></title>
    <div class="container">
        <?php $this->renderSection('main_content') ?>
    </div>
    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <!-- Bootstrap Colorpicker -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo base_url(); ?>/public/css/build/css/custom.min.css" rel="stylesheet">

    <link href="cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>/public/css/stylesheets.css" rel="stylesheet" type="text/css" />

    <script type='text/javascript' src="<?php echo base_url(); ?>/public/themes/Acadian/assets/js/ckeditor.js"></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/uniform/jquery.uniform.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/knob/jquery.knob.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/sparkline/jquery.sparkline.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/flot/jquery.flot.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/flot/jquery.flot.resize.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/actions.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/charts.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/datatables/jquery.dataTables.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/tinymce/tinymce.min.js'></script>
    <script src="<?php echo base_url(); ?>/public/plugins/daterangepicker/daterangepicker.js"></script>
    <link type="text/css" href="<?php echo base_url(); ?>/public/newtheme/js/plugins/dropzone.min/dropzone.min.css" rel="stylesheet" />
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/dropzone.min/dropzone.min.js'></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>/public/plugins/daterangepicker/daterangepicker.css">

    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/uniform/jquery.uniform.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/flot/jquery.flot.pie.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/fancybox/jquery.fancybox.pack.js'></script>

    <!-- <script type='text/javascript' src='https://code.jquery.com/jquery-3.5.1.js' ></script> -->
    <script type='text/javascript' src='https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js'></script>
    <script type='text/javascript' src='https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js'></script>
</head>

<body>
    <div class="container body">
        <div class="right_col" role="main">
            <div class="col-md-12">
                <div class="x_title">
                    <p class="brief">
                    <h2 class="brief"><?= $title ?></h2>
                    </p>
                </div>

                <?php if (strlen($content[0]['objectives']) > 5) {
                    echo '<b>Objectives</b><br>At the end of this course you will be able to:';
                    echo $content[0]['objectives'];
                    echo '<br>';
                }
                if (strlen($content[0]['description']) > 5) {
                    echo '<b>Description</b>';
                    echo $content[0]['description'];
                } ?>
            </div>
        </div>
    </div>
    <!-- Bootstrap -->
    <script src="<?php echo base_url(); ?>/public/css/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DateJS -->
    <script src="<?php echo base_url(); ?>/public/css/vendors/DateJS/build/date.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?php echo base_url(); ?>/public/css/vendors/moment/min/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>/public/css/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-datetimepicker -->
    <script src="<?php echo base_url(); ?>/public/css/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Select2 -->
    <script src="<?php echo base_url(); ?>/public/css/vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- jQuery Tags Input -->
    <!-- <script src="<?php echo base_url(); ?>/public/css/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script> -->
    <!-- Switchery -->
    <!-- <script src="<?php echo base_url(); ?>/public/css/vendors/switchery/dist/switchery.min.js"></script> -->
    <!-- Parsley -->
    <!-- <script src="<?php echo base_url(); ?>/public/css/vendors/parsleyjs/dist/parsley.min.js"></script> -->
    <!-- Autosize -->
    <!-- <script src="<?php echo base_url(); ?>/public/css/vendors/autosize/dist/autosize.min.js"></script> -->
    <!-- NProgress -->
    <script src="<?php echo base_url(); ?>/public/css/vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="<?php echo base_url(); ?>/public/css/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="<?php echo base_url(); ?>/public/css/build/js/custom.min.js"></script>


</body>

</html>