<?= $this->include('templates/header_view') ?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>Demos</li><b>&nbsp;>&nbsp;</b>
            <li class="active">TQ Featured</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="content">
                    <form action="<?php echo base_url('dochek_email/assign_demo') ?>" method="POST" autocomplete="off"><?= csrf_field() ?>
                        <div class="form-row">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="emailid" placeholder="emailid" required="" />
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="comment" placeholder="comment" />
                            </div>
                            <input type='hidden' name='demo_id' id="result" />

                            <div class="col-md-2">
                                <div class="input-group">
                                    <input id="birthday" name="demo_date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" required="required" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 60000);
                                        }
                                    </script>
                                </div>

                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input type="text" name="access_code" placeholder="App Username" class="form-control" required="" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <button type="submit" name="assign_demo" class="btn btn-primary form-control">
                                        <i class="icon-key"></i> Assign Demo
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="assigndemo" value="1">
                        <input type="hidden" name="username" value="<?php echo session()->get('username'); ?>">
                        <input type="hidden" name="user_uid" value="<?php echo session()->get('id_user'); ?>">
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">

        <div class="clearfix">
            <div class="pull-right tableTools-container">

            </div>
        </div>
        <div>
            <div class="x_panel">
                <div class="block block-drop-shadow">
                    <div class="content">
                        <table id="example" class="table  table-sm table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Project Name</th>
                                    <th>Description</th>
                                    <th>Case Study</th>
                                    <th>Demo Video</th>
                                    <th>Course Link</th>
                                    <th>Details</th>

                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if ($featuredData) {
                                    foreach ($featuredData as $eachfeatured_vc) {
                                        // print_r($eachfeatured_vc);
                                        $demoid = $eachfeatured_vc['resultnewcheck'][0]['demoid'];
                                        $description = $eachfeatured_vc['resultnewcheck'][0]['description'];
                                        $show = false;

                                        echo "<tr>";
                                        echo "<td><input type='checkbox' name='demoidarray' data-id='" . $demoid . "' class='demoidarray'/> </td>";
                                        echo "<td>";
                                        echo $description;

                                        echo "</td><td>";
                                        if (strlen($eachfeatured_vc['casestudy']) > 2) {
                                            echo $eachfeatured_vc['casestudy'];
                                        }
                                        echo "</td><td>";
                                        if (strlen($eachfeatured_vc['casestudy_pdf']) > 2) {
                                            echo "<button type=\"submit\" style=\"width: 66px;\" class=\"btn btn-link\" onClick=\"target_popup2('";
                                            echo $eachfeatured_vc['casestudy_pdf'];
                                            echo "', '" .  $demoid . "')\">";
                                            echo '<img src="images/pdf.jpg" height="30" />';
                                            echo '</button>';
                                        }
                                        echo "</td><td style='width: 164PX;'>";
                                        if (strlen($eachfeatured_vc['vid']) > 2) {

                                            echo "<button type=\"submit\" class=\"btn btn-link\" style=\"width: 70px;\" onClick=\"target_popup4('";
                                            echo $eachfeatured_vc['vid'];
                                            echo "', '" . $eachfeatured_vc['demoid'] . "')\">";
                                            echo '<img src="images/vid.jpg" height="30" />';
                                            echo '</button>';
                                        }
                                        if (strlen($eachfeatured_vc['vidx']) > 2) {

                                            echo "<button type=\"submit\" class=\"btn btn-sm btn-success\" onClick=\"target_popup1('";
                                            echo $eachfeatured_vc['vidx'];
                                            echo "')\">";
                                            echo '<i class="icon-play"></i>';
                                            echo '</button>';
                                        }
                                        echo "</td><td>";

                                        if (strlen($eachfeatured_vc['courselink']) > 2) {
                                            echo "<button type=\"submit\" class=\"btn btn-sm btn-success\" onClick=\"target_popup1('";
                                            echo $eachfeatured_vc['courselink'];
                                            echo "')\">";
                                            echo '<i class="icon-play"></i>';
                                            echo '</button>';
                                        }
                                        echo "</td><td>";
                                        echo "<button type=\"submit\" class=\"btn btn-sm btn-warning\" onClick=\"target_popup3('";
                                        echo  $demoid;
                                        echo "')\">";
                                        echo '<i class="icon-th-list"></i>';
                                        echo '</button>';
                                        echo "</td>";
                                        //echo "<td></td>";
                                        /* echo "<form method='POST' action='view_category.php'>";
                                       echo "<input type='hidden' name='unique_ids' value='$demoid'/>";
                                       echo "<input type='hidden' name='product_name' value='$description'/>";
                                       echo "<input type='hidden' name='description' value='$casestudy'/>";
                                       echo "<input type='hidden' name='pdf'  value='$casestudy_pdf' name='product_name'/>";
                                       echo "<input type='hidden' name='video_two' value='$vidx' name='product_name'/>";
                                       echo "<input type='hidden' name='video_one' value='$vid' name='product_name'/>";
                                       echo "<input type='hidden' name='course_link' value='$courselink' name='product_name'/>";
                                       echo "<input type='hidden' name='cart_button' value='add'/>";
                                       echo "<td><button type='submit' class='btn btn-default btn-clean'><i class='icon-shopping-cart'></i></button></td>";
                                       echo "</form>"; */
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
</div>
<script>
    $(document).ready(function() {

        //$('#example').DataTable();
        $("#direct_load").hide();
        $("#example").show();
        $(".hide_direct_load").show();

        var demoid_array = [];

        $('.demoidarray').on('change', function() {

            if ($(this).is(":checked")) {
                demoid_array.push($(this).data("id"));
            } else {
                var index = demoid_array.indexOf($(this).data("id"));
                if (index > -1) {
                    demoid_array.splice(index, 1);
                }

            }
            $('#result').val(demoid_array.join(","));
        });

        $("#direct_ok").click(function() {

            $("#direct_load").hide();
            $("#example").show();
            $(".hide_direct_load").show();
        });

        if ($("#direct_load").is(":visible") == true) {
            $("#example").hide();
            $(".hide_direct_load").hide();
        }
    });


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
        newwin = window.open(window.location.origin + '/upload/client/' + demoid + '/' + filename, 'windowname5', params);
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
<?= $this->include('templates/footer_view') ?>