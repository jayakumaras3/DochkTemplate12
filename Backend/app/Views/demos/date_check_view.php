<html lang="en">
    <head>        
        <title>DoChek</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="icon" type="newtheme/image/ico" href="<?php echo base_url(); ?>/public/img/favicon.ico"/>

        <link href="<?php echo base_url(); ?>/public/newtheme/css/stylesheets.css" rel="stylesheet" type="text/css" />        

        <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/jquery/jquery.min.js'></script>


        <script>
            $(document).ready(function () {

                $("#close_access_empty").click(function () {
                    $("#access_empty_model").hide();
                });
                $("#ok_access_empty").click(function () {
                    $("#access_empty_model").hide();
                });
                $("#close_access_check").click(function () {
                    $("#access_check").hide();
                });
                $("#ok_access_check").click(function () {
                    $("#access_check").hide();
                });
                $("#close_date_check").click(function () {
                    $("#date_check").hide();
                });
                $("#ok_date_check").click(function () {
                    $("#date_check").hide();
                });

            });
        </script>
    </head>
    <div id="date_check" class="modal-dialog">
<div class="modal-content">                
    <div class="modal-header">
        <button id="close_date_check" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Demos expired please contact admininstrator !</h4>
    </div>                
    <div class="modal-footer">
        <button id="ok_date_check" type="button" class="btn btn-warning btn-clean" data-dismiss="modal">Ok</button>              
    </div>
</div>
    </div>
</html>