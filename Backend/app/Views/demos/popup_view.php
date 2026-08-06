<!DOCTYPE html>
<html lang="en">

<head>
    <title>DoChek</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
                echo '<tr><td class="span1" style="background:#2C3D4F;">Project Name</td><td>';
                echo $result['projectname'];
                echo '</td></tr>';
                echo '<tr><td class="span1" style="background:#2C3D4F;">Description</td><td>';
                echo $result['Description'];
                echo '</td></tr>';
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

<style>
    td.span1 {
        width: 30%;
    }
</style>

</html>