<!DOCTYPE html>
<html lang="en">

<head>
    <title>DoChek</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="icon" type="newtheme/image/ico" href="<?php echo base_url(); ?>/public/img/favicon.ico"/>

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


    <style type="text/css">
        .badge1 {
            position: relative;
        }

        .badge1[data-badge]:after {
            content: attr(data-badge);
            position: absolute;
            top: -7px;
            right: -10px;
            font-size: .7em;
            background: orangered;
            color: white;
            width: 18px;
            height: 18px;
            text-align: center;
            border-radius: 50%;

        }

        .row {
            margin-left: 0px !important;
            margin-right: 0px !important
        }
    </style>

</head>

<div>

    <body class="login">
        <div class="container" style="padding: 40px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="block block-drop-shadow">
                            <table class="table table-sm table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Description</th>
                                        <th>Case Study</th>
                                        <th>Demo Video</th>
                                        <th>Course Link</th>
                                        <th><?=  lang('UI_Text.Action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($getallcat) {
                                        foreach ($getallcat as $geteachallcat) {
                                            echo "<tr>";
                                            echo "<td>";

                                            $description = $geteachallcat['description'];
                                            echo $description; //Product Name
                                            echo "</td><td>";
                                            $casestudy = $geteachallcat['casestudy'];
                                            echo $casestudy; //Description
                                            echo "</td><td>";
                                            $casestudy_pdf = $geteachallcat['casestudy_pdf'];
                                            if (strlen($casestudy_pdf) > 2) {
                                                echo "<button type=\"submit\" style=\"width: 66px;\" class=\"btn btn-link\" onClick=\"target_popup2('";
                                                echo $casestudy_pdf;
                                                echo "', '" . $demoid . "')\">";
                                                echo '<img src="images/pdf.jpg" height="30" />';
                                                echo '</button>';
                                            }
                                            echo "</td><td>";
                                            $vid = $geteachallcat['vid'];
                                            if (strlen($vid) > 2) {



                                                echo "<button type=\"submit\" class=\"btn btn-link\" style=\"width: 70px;\" onClick=\"target_popup4('";
                                                echo $vid;
                                                echo "', '" . $demoid . "')\">";
                                                echo '<img src="images/vid.jpg" height="30" />';
                                                echo '</button>';
                                            }
                                            $vidx = $geteachallcat['vidx'];
                                            if (strlen($vidx) > 2) {

                                                echo "<button type=\"submit\" class=\"btn btn-sm btn-success\" onClick=\"target_popup1('";
                                                echo $vidx;
                                                echo "')\">";
                                                echo '<i class="icon-play"></i>';
                                                echo '</button>';
                                            }
                                            echo "</td><td>";

                                            $courselink = $geteachallcat['courselink'];
                                            if (strlen($courselink) > 2) {

                                                /*$Link1 = "https://chek.dochek.com/demo/tqlibrary/011/scormcontent/index.html";
                                                $Link2 = "https://chek.dochek.com/demo/tqlibrary/001/scormcontent/index.html";
                                                $Link3 = "https://chek.dochek.com/demo/tqlibrary/006/scormcontent/index.html";
                                        
                                                if($courselink == $Link1 || $courselink == $Link2 || $courselink == $Link3){
                                                echo "<a  class=\"btn btn-success\" target='_blank' href='";
                                                echo $courselink;
                                                echo "' />";
                                                echo 'Launch';
                                                echo '</a>';
                                                }else{*/

                                                echo "<button type=\"submit\" class=\"btn btn-sm btn-success\"  onClick=\"target_popup1('";
                                                echo $courselink;
                                                echo "')\">";
                                                echo '<span class="icon-play"></span>';
                                                echo '</button>';
                                                /*}*/
                                            }
                                            echo "</td><td>";
                                            echo "<button type=\"submit\" class=\"btn btn-sm btn-warning\" onClick=\"target_popup3('";
                                            echo $geteachallcat['demoid'];
                                            echo "')\">";
                                            echo '<i class="icon-th-list"></i>';
                                            echo '</button>';
                                            echo "</td>";
                                            //echo "<td><i class='icon-shopping-cart'></i></td>";
                                            echo "</form>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</div>

</html>



<script>
    $(document).ready(function() {
        var spanSubmit = $('.icon-shopping-cart');

        spanSubmit.on('click', function() {
            $(this).closest('form').submit();
            // Will also work, but might fail if nesting is changed:
            // $(this).parent().submit();
        });

        $(document).bind("contextmenu", function(e) {
            return false;
        });
    });


    /*function target_popup1(url) {

        url = url.trim();

        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        newwin = window.open(url, 'windowname4', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }*/
    var newpage;

    function target_popup1(url) {

        url = url.trim();

        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        console.log("target_popup1 " + url);
        $.post('category_schedule/demo_loader', {
            url: url
        }, function(result) {
            newpage = url;
            newwin = window.open('category_schedule/demo_loader', 'windowname5', params);

            if (window.focus) {
                newwin.focus();
            }
            return false;
        });
    }

    function disableContextMenu() {
        window.frames["customframe"].document.oncontextmenu = function() {
            alert("No way!");
            return false;
        };
        // Or use this
        // document.getElementById("fraDisabled").contentWindow.document.oncontextmenu = function(){alert("No way!"); return false;};;
    }

    function target_popup2(filename, demoid) {

        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        var str = window.location.pathname;
        var res = str.split("/");
        var url = window.location.origin + '/upload/client/' + demoid + '/' + filename;
        $.post('category_schedule/demo_loader', {
            url: url
        }, function(result) {
            newpage = url;
            newwin = window.open('category_schedule/demo_loader', 'windowname5', params);

            if (window.focus) {
                newwin.focus();
            }
            return false;
        });


    }

    function target_popup3(demoid) {

        params = 'width=800';
        params += ', height=500';
        params += ', top=0, left=0'
        params += ', fullscreen=no';

        var url = 'popup_ws?demoid=' + demoid;
        $.post('category_schedule/demo_loader', {
            url: url
        }, function(result) {
            newpage = url;
            newwin = window.open('category_schedule/demo_loader', 'windowname8', params);

            if (window.focus) {
                newwin.focus();
            }
            return false;
        });

    }

    function target_popup4(filename, demoid) {

        params = 'width=1000';
        params += ', height=660';
        params += ', top=0, left=0'
        params += ', fullscreen=no';

        var url = 'popup_vid_ws?demoid=' + demoid + '&filename=' + filename;
        console.log("target_popup4 " + url);
        $.post('category_schedule/demo_loader', {
            url: url
        }, function(result) {
            newpage = url;
            newwin = window.open('category_schedule/demo_loader', 'windowname5', params);

            if (window.focus) {
                newwin.focus();
            }
            return false;
        });

    }
</script>
</body>