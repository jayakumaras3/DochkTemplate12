<html lang="en">

<head>
    <title>DoChek</title>
    <link rel="icon" type="newtheme/image/ico" href="<?php echo base_url(); ?>/public/img/favicon.ico"/>

    <!-- <link href="<?php echo base_url() ?>/public/newtheme/css/stylesheets.css" rel="stylesheet" type="text/css" />         -->
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
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/bootstrap/bootstrap.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
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
</head>
<div class="col-xs-12">
    <div class="block block-drop-shadow">
        <table class=" table table-striped table-bordered" style="color:white">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //$num_rowsc = count($result);
                foreach ($result as $row) {
                    echo '<tr>';
                    echo '<td class="span1" style="background:#2C3D4F;">';
                    echo isset($row['valuedesc']) ? $row['valuedesc'] : '';
                    echo '</td>';
                    echo '<td>';
                    echo isset($row['getids']) ? $row['getids'] : '';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {

        $(document).bind("contextmenu", function(e) {
            return false;
        });
    });
</script>

</html>