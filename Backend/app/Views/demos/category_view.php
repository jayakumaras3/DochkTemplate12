<?php
$user = session()->get('username');

?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>Demos</li><b> &nbsp; > &nbsp;</b>
            <li class="active">Demo List</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="x_panel">
        <div class="col-xs-12">

            <div class="clearfix">
                <div class="pull-right tableTools-container"></div>
            </div>
            <div>
                <div class="block block-drop-shadow">
                    <div class="content">
                        <table id="searchdatatable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project Name</th>
                                    <th>Description</th>
                                    <th>Case Study</th>
                                    <th>Demo Video</th>
                                    <th>Course Link</th>
                                    <th>Details</th>
                                    <th>Select</th>

                                </tr>
                            </thead>
                            <?php
                            if ($searchval != 1) {
                                if ($postval == 0) { ?>
                                    <!-- <div id="direct_load" class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body clearfix">
                                            "Are you sure you want to list all the demos? This may take some time to list all."
                                        </div>
                                        <div class="modal-footer">
                                            <button id="direct_ok" type="button" class="btn btn-warning btn-clean" data-dismiss="modal">Ok</button>
                                        </div>
                                    </div>
                                </div> -->
                                    <tbody>
                                        <?php
                                        // print_r(count($getallcat_vc));
                                        if ($tqDemoAccess) {
                                            //print_r($getallcat_vc);
                                            //exit();
                                            if ($getallcat_vc) {

                                                $conter = 1;
                                                foreach ($getallcat_vc as $eachallcat_vc) {

                                                    $demoid = $eachallcat_vc['demoid'];
                                                    $description = $eachallcat_vc['description'];
                                                    //print_r($demoid);
                                                    // exit();
                                                    $show = false;

                                                    echo "<tr>";
                                                    echo "<td>$conter</td>";
                                                    echo "<td>";
                                                    if (strlen($eachallcat_vc['demo_det']) > 2) {
                                                        echo $eachallcat_vc['demo_det'];
                                                    } else {
                                                        echo $eachallcat_vc['description'];
                                                    }
                                                    echo "</td><td>";
                                                    if (strlen($eachallcat_vc['casestudy']) > 2) {
                                                        echo $eachallcat_vc['casestudy'];
                                                    }
                                                    echo "</td><td>";
                                                    if (strlen($eachallcat_vc['casestudy_pdf']) > 2) {
                                                        echo "<button type=\"submit\" style=\"width: 66px;\" class=\"btn btn-link\" onClick=\"target_popup2('";
                                                        echo $eachfeatured_vc['casestudy_pdf'];
                                                        echo "', '" .  $demoid . "')\">";
                                                        echo '<img src="images/pdf.jpg" height="30" />';
                                                        echo '</button>';
                                                    }
                                                    echo "</td><td style='width: 164PX;'>";
                                                    if (strlen($eachallcat_vc['vid']) > 2) {

                                                        echo "<button type=\"submit\" class=\"btn btn-link\" style=\"width: 70px;\" onClick=\"target_popup4('";
                                                        echo $eachallcat_vc['vid'];
                                                        echo "', '" . $eachallcat_vc['demoid'] . "')\">";
                                                        echo '<img src="images/vid.jpg" height="30" />';
                                                        echo '</button>';
                                                    }
                                                    if (strlen($eachallcat_vc['vidx']) > 2) {

                                                        echo "<button type=\"submit\" class=\"widget-icon  btn-success\" onClick=\"target_popup1('";
                                                        echo $eachallcat_vc['vidx'];
                                                        echo "')\">";
                                                        echo '<i class="icon-play"></i>';
                                                        echo '</button>';
                                                    }
                                                    echo "</td><td>";

                                                    if (strlen($eachallcat_vc['courselink']) > 2) {
                                                        echo "<button type=\"submit\" class=\"widget-icon  btn-success\" onClick=\"target_popup1('";
                                                        echo $eachallcat_vc['courselink'];
                                                        echo "')\">";
                                                        echo '<i class="icon-play"></i>';
                                                        echo '</button>';
                                                    }
                                                    echo "</td><td>";
                                                    echo "<button type=\"submit\" class=\"widget-icon  btn-warning\" onClick=\"target_popup3('";
                                                    echo  $demoid;
                                                    echo "')\">";
                                                    echo '<i class="icon-th-list"></i>';
                                                    echo '</button>';
                                                    echo "</td>";
                                                    //echo "<td></td>";
                                                    $casestudy = $eachallcat_vc['casestudy'];
                                                    $casestudy_pdf = $eachallcat_vc['casestudy_pdf'];
                                                    $vidx = $eachallcat_vc['vidx'];
                                                    $vid = $eachallcat_vc['vid'];
                                                    $courselink = $eachallcat_vc['courselink'];
                                                    echo "<td><form id='td" . $demoid . "' method='POST' action='" . base_url('demos/view_category?searchval=2') . "'>";
                                                    echo "<input type='hidden' name='unique_ids' value='$demoid'/>";
                                                    echo "<input type='hidden' name='product_name' value='$description'/>";
                                                    echo "<input type='hidden' name='description' value='$casestudy'/>";
                                                    echo "<input type='hidden' name='pdf'  value='$casestudy_pdf' name='product_name'/>";
                                                    echo "<input type='hidden' name='video_two' value='$vidx' name='product_name'/>";
                                                    echo "<input type='hidden' name='video_one' value='$vid' name='product_name'/>";
                                                    echo "<input type='hidden' name='course_link' value='$courselink' name='product_name'/>";
                                                    echo "<input type='hidden' name='cart_button' value='add'/>";
                                                    echo "</form>";
                                                    echo "<input class = \"demoselected\" data-id=\"" . $demoid . "\" data-activate=\"true\" type=\"checkbox\" id=\"check" . $demoid . "\" name=\"demo_selected\"></td>";
                                                    echo "</tr>";

                                                    $conter = $conter + 1;
                                                }
                                            } else {
                                                if ($getsearchallcat_vc) {
                                                    $conter = 1;
                                                    foreach ($getsearchallcat_vc as $eachallcat_vc) {
                                                        if ($eachallcat_vc) {
                                                            $demoid = $eachallcat_vc[0]['demoid'];
                                                            $description = $eachallcat_vc[0]['description'];
                                                            $show = false;

                                                            echo "<tr>";
                                                            echo "<td>$conter</td>";
                                                            echo "<td>";
                                                            if (strlen($eachallcat_vc[0]['demo_det']) > 2) {
                                                                echo $eachallcat_vc[0]['demo_det'];
                                                            } else {
                                                                echo $eachallcat_vc[0]['description'];
                                                            }
                                                            echo "</td><td>";
                                                            if (strlen($eachallcat_vc[0]['casestudy']) > 2) {
                                                                echo $eachallcat_vc[0]['casestudy'];
                                                            }
                                                            echo "</td><td>";
                                                            if (strlen($eachallcat_vc[0]['casestudy_pdf']) > 2) {
                                                                echo "<button type=\"submit\" style=\"width: 66px;\" class=\"btn btn-link\" onClick=\"target_popup2('";
                                                                echo $eachallcat_vc[0]['casestudy_pdf'];
                                                                echo "', '" .  $demoid . "')\">";
                                                                echo '<img src="images/pdf.jpg" height="30" />';
                                                                echo '</button>';
                                                            }
                                                            echo "</td><td style='width: 164PX;'>";
                                                            if (strlen($eachallcat_vc[0]['vid']) > 2) {

                                                                echo "<button type=\"submit\" class=\"btn btn-link\" style=\"width: 70px;\" onClick=\"target_popup4('";
                                                                echo $eachallcat_vc[0]['vid'];
                                                                echo "', '" . $eachallcat_vc[0]['demoid'] . "')\">";
                                                                echo '<img src="images/vid.jpg" height="30" />';
                                                                echo '</button>';
                                                            }
                                                            if (strlen($eachallcat_vc[0]['vidx']) > 2) {

                                                                echo "<button type=\"submit\" class=\"btn btn-success\" onClick=\"target_popup1('";
                                                                echo $eachallcat_vc[0]['vidx'];
                                                                echo "')\">";
                                                                echo 'Launch';
                                                                echo '</button>';
                                                            }
                                                            echo "</td><td>";

                                                            if (strlen($eachallcat_vc[0]['courselink']) > 2) {
                                                                echo "<button type=\"submit\" class=\"widget-icon btn-success\" onClick=\"target_popup1('";
                                                                echo $eachallcat_vc[0]['courselink'];
                                                                echo "')\">";
                                                                echo '<i class="icon-play"></i>';
                                                                echo '</button>';
                                                            }
                                                            echo "</td><td>";
                                                            echo "<button type=\"submit\" class=\"widget-icon btn-warning\" onClick=\"target_popup3('";
                                                            echo  $demoid;
                                                            echo "')\">";
                                                            echo '<i class="icon-th-list"></i>';
                                                            echo '</button>';
                                                            echo "</td>";
                                                            //echo "<td></td>";
                                                            $casestudy = $eachallcat_vc[0]['casestudy'];
                                                            $casestudy_pdf = $eachallcat_vc[0]['casestudy_pdf'];
                                                            $vidx = $eachallcat_vc[0]['vidx'];
                                                            $vid = $eachallcat_vc[0]['vid'];
                                                            $courselink = $eachallcat_vc[0]['courselink'];

                                                            echo "<td><form id='td" . $demoid . "' method='POST' action='" . base_url('demos/view_category?searchval=2') . "'>";
                                                            echo "<input type='hidden' name='unique_ids' value='$demoid'/>";
                                                            echo "<input type='hidden' name='product_name' value='$description'/>";
                                                            echo "<input type='hidden' name='description' value='$casestudy'/>";
                                                            echo "<input type='hidden' name='pdf'  value='$casestudy_pdf' name='product_name'/>";
                                                            echo "<input type='hidden' name='video_two' value='$vidx' name='product_name'/>";
                                                            echo "<input type='hidden' name='video_one' value='$vid' name='product_name'/>";
                                                            echo "<input type='hidden' name='course_link' value='$courselink' name='product_name'/>";
                                                            echo "<input type='hidden' name='cart_button' value='add'/>";
                                                            echo "</form>";
                                                            echo "<input class = \"demoselected\" data-id=\"" . $demoid . "\" data-activate=\"true\" type=\"checkbox\" id=\"check" . $demoid . "\" name=\"demo_selected\"></td>";



                                                            echo "</tr>";

                                                            $conter = $conter + 1;
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            if (count($getallcat_vc) > 0) {
                                            } else {
                                                if ($getallcat_vc) {
                                                    $conter = 1;
                                                    foreach ($getallcat_vc as $eachallcat_vc) {
                                                        // print_r($eachfeatured_vc);
                                                        $demoid = $eachallcat_vc['demoid'];

                                                        $description = $eachallcat_vc['description'];
                                                        $show = false;

                                                        echo "<tr>";
                                                        echo "<td>$conter</td>";
                                                        echo "<td>";
                                                        if (strlen($eachallcat_vc['demo_det']) > 2) {
                                                            echo $eachallcat_vc['demo_det'];
                                                        } else {
                                                            echo $eachallcat_vc['description'];
                                                        }
                                                        echo "</td><td>";
                                                        if (strlen($eachallcat_vc['casestudy']) > 2) {
                                                            echo $eachallcat_vc['casestudy'];
                                                        }
                                                        echo "</td><td>";
                                                        if (strlen($eachallcat_vc['casestudy_pdf']) > 2) {
                                                            echo "<button type=\"submit\" style=\"width: 66px;\" class=\"btn btn-link\" onClick=\"target_popup2('";
                                                            echo $eachfeatured_vc['casestudy_pdf'];
                                                            echo "', '" .  $demoid . "')\">";
                                                            echo '<img src="images/pdf.jpg" height="30" />';
                                                            echo '</button>';
                                                        }
                                                        echo "</td><td style='width: 164PX;'>";
                                                        if (strlen($eachallcat_vc['vid']) > 2) {

                                                            echo "<button type=\"submit\" class=\"btn btn-link\" style=\"width: 70px;\" onClick=\"target_popup4('";
                                                            echo $eachallcat_vc['vid'];
                                                            echo "', '" . $eachallcat_vc['demoid'] . "')\">";
                                                            echo '<img src="images/vid.jpg" height="30" />';
                                                            echo '</button>';
                                                        }
                                                        if (strlen($eachallcat_vc['vidx']) > 2) {

                                                            echo "<button type=\"submit\" class=\"widget-icon btn-success\" onClick=\"target_popup1('";
                                                            echo $eachallcat_vc['vidx'];
                                                            echo "')\">";
                                                            echo '<i class="icon-play"></i>';
                                                            echo '</button>';
                                                        }
                                                        echo "</td><td>";

                                                        if (strlen($eachallcat_vc['courselink']) > 2) {
                                                            echo "<button type=\"submit\" class=\"widget-icon btn-success\" onClick=\"target_popup1('";
                                                            echo $eachallcat_vc['courselink'];
                                                            echo "')\">";
                                                            echo '<i class="icon-play"></i>';
                                                            echo '</button>';
                                                        }
                                                        echo "</td><td>";
                                                        echo "<button type=\"submit\" class=\"widget-icon btn-warning\" onClick=\"target_popup3('";
                                                        echo  $demoid;
                                                        echo "')\">";
                                                        echo '<i class="icon-th-list"></i>';
                                                        echo '</button>';
                                                        echo "</td>";
                                                        //echo "<td></td>";
                                                        $casestudy = $eachallcat_vc['casestudy'];
                                                        $casestudy_pdf = $eachallcat_vc['casestudy_pdf'];
                                                        $vidx = $eachallcat_vc['vidx'];
                                                        $vid = $eachallcat_vc['vid'];
                                                        $courselink = $eachallcat_vc['courselink'];
                                                        echo "<td><form  id='td" . $demoid . "' method='POST' action='" . base_url('demos/view_category?searchval=2') . "'>";
                                                        echo "<input type='hidden' name='unique_ids' value='$demoid'/>";
                                                        echo "<input type='hidden' name='product_name' value='$description'/>";
                                                        echo "<input type='hidden' name='description' value='$casestudy'/>";
                                                        echo "<input type='hidden' name='pdf'  value='$casestudy_pdf' name='product_name'/>";
                                                        echo "<input type='hidden' name='video_two' value='$vidx' name='product_name'/>";
                                                        echo "<input type='hidden' name='video_one' value='$vid' name='product_name'/>";
                                                        echo "<input type='hidden' name='course_link' value='$courselink' name='product_name'/>";
                                                        echo "<input type='hidden' name='cart_button' value='add'/>";
                                                        echo "</form>";
                                                        echo "<input class = \"demoselected\" data-id=\"" . $demoid . "\" data-activate=\"true\" type=\"checkbox\" id=\"check" . $demoid . "\" name=\"demo_selected\"></td>";

                                                        echo "</tr>";

                                                        $conter = $conter + 1;
                                                    }
                                                }
                                            }
                                        }

                                        ?>
                                    </tbody>
                            <?php }
                            } ?>
                        </table>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12"><span id="errormeg" style="background: #8e0303;padding:5px;border-radius: 3px;"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function target_popup1(url) {

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
    }

    function target_popup2(filename, demoid) {

        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        var str = window.location.pathname;
        var res = str.split("/");
        newwin = window.open(window.location.origin + '/v3/upload/client/' + demoid + '/' + filename, 'windowname5', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }

    function target_popup3(demoid) {

        params = 'width=800';
        params += ', height=500';
        params += ', top=0, left=0'
        params += ', fullscreen=no';
        newwin = window.open('<?php echo base_url() ?>/demos/popup?demoid=' + demoid, 'windowname8', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }

    function target_popup4(filename, demoid) {

        params = 'width=1000';
        params += ', height=660';
        params += ', top=0, left=0'
        params += ', fullscreen=no';
        newwin = window.open('<?php echo base_url() ?>/demos/popup_vid?demoid=' + demoid + '&filename=' + filename, 'windowname5', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }
    
</script>