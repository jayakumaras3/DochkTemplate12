<html lang="en">
    <head>        
        <title>DoChek</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="icon" type="image/ico" href="favicon.ico"/>

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
    <?php if(isset($comment)){
        echo'<div id="date_check" class="modal-dialog">
            <div class="modal-content">                
                <div class="modal-header">
                    <button id="close_date_check" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">'.$comment.'</h4>
                </div>                
                <div class="modal-footer">
                    <button id="ok_date_check" type="button" class="btn btn-warning btn-clean" data-dismiss="modal">Ok</button>              
                </div>
            </div>
        </div>';
    }
    ?>
    <body class="bg-img-num1"> 
        <div class="container">        
            <div id="login_contain" class="login-block">
                <div class="block block-transparent">
                    <div class="content controls npt">
                        <form method="POST"><?= csrf_field() ?>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <span class="icon-key"></span>
                                        </div>
                                        <input type="password" name="access_code" class="form-control" placeholder="Accesscode"/>
                                    </div>
                                </div>
                            </div>                        
                            <div class="form-row">
                                <div class="col-md-6">
                                    <button type="submit" name="access_check" class="btn btn-default btn-block btn-clean">Log In</button>
                                </div>
                            </div>   
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>