<div class="row">

    <div class="col-xs-12">
        <div class="col-xs-12">
            <div class="block block-drop-shadow">
                <div class="content">
                    <div class="x_panel">
                        <form action="<?php echo base_url('dochek_email/assign_demo') ?>" method="POST" autocomplete="off"><?= csrf_field() ?>
                            <div class="form-row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="emailid" placeholder="emailid" required="" />
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="comment" placeholder="comment" />
                                </div>
                                <?php
                                if (isset($_SESSION["dochk_cart_item"])) {
                                    $demid = array();
                                    foreach ($_SESSION["dochk_cart_item"] as $val) {
                                        $demid[] = $val['demo_id'];
                                    }
                                ?>
                                    <input type="hidden" name="demo_id" value="<?php echo implode(",", $demid); ?>">
                                <?php
                                }
                                ?>
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
                    <form action="<?php echo base_url('demos/mycart') ?>" method="POST"><?= csrf_field() ?>
                        <div class="form-group col-md-12">
                            <input type="hidden" name="empty_process" value="empty">
                            <button type="submit" class="btn btn-sm btn-warning pull-right">
                                <i class="icon-shopping-cart"></i> Empty Cart
                            </button>
                        </div>
                    </form>

                    <div>
                        <div class="x_panel">
                            <div class="block block-drop-shadow">
                                <div class="content">
                                    <table class="table table-sm table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Description</th>
                                                <th>Case Study</th>
                                                <th>Demo Video</th>
                                                <th>Course Link</th>
                                                <th>Details</th>
                                                <th>Remove <i class='icon-shopping-cart'></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($_SESSION["dochk_cart_item"])) {
                                                $z = 1;
                                                //echo '<pre>';
                                                //print_r($_SESSION["dochk_cart_item"]);
                                                foreach ($_SESSION["dochk_cart_item"] as $item) {
                                                    $dem_id = $item['demo_id'];
                                                    echo "<tr><td>";
                                                    echo $item['product_name'];
                                                    echo "</td><td>";
                                                    echo $item['description'];
                                                    echo "</td><td>";

                                                    if (!empty($item['case_study'])) {
                                                        echo "<button type=\"submit\" style=\"width: 66px;\" class=\"btn btn-link\" onClick=\"target_popup2('";
                                                        echo $item['case_study'];
                                                        echo "', '" . $dem_id . "')\">";
                                                        echo '<img src="images/pdf.jpg" height="30" />';
                                                        echo '</button>';
                                                    }
                                                    echo "</td><td>";

                                                    if (!empty($item['video_one'])) {
                                                        echo "<button type=\"submit\" class=\"btn btn-link\" style=\"width: 70px;\" onClick=\"target_popup4('";
                                                        echo $item['video_one'];
                                                        echo "', '" . $dem_id . "')\">";
                                                        echo '<img src="images/vid.jpg" height="30" />';
                                                        echo '</button>';
                                                    }

                                                    if (!empty($item['video_launch'])) {
                                                        echo "<button type=\"submit\" class=\"btn btn-sm btn-success\" onClick=\"target_popup1('";
                                                        echo $item['video_launch'];
                                                        echo "')\">";
                                                        echo '<i class="icon-play"></i>';
                                                        echo '</button>';
                                                    }
                                                    echo "</td><td>";

                                                    if (!empty($item['course_link'])) {
                                                        echo "<button type=\"submit\" class=\"btn btn-sm btn-success\" onClick=\"target_popup1('";
                                                        echo $item['course_link'];
                                                        echo "')\">";
                                                        echo '<i class="icon-play"></i>';
                                                        echo '</button>';
                                                    }

                                                    echo "</td><td>";

                                                    echo "<button type=\"submit\" class=\"btn btn-sm btn-warning\" onClick=\"target_popup3('";
                                                    echo $dem_id;
                                                    echo "')\">";
                                                    echo '<i class="icon-th-list"></i>';
                                                    echo '</button>';
                                                    echo "</td><td>";
                                                    echo "<form method='POST' action='" . base_url('demos/mycart') . "'>";
                                                    echo "<input type='hidden' name='remove_demoid' value='$dem_id'>";
                                                    echo "<input type='hidden' name='remove_process' value='remove'>";
                                                    echo "<button type='submit' class='widget-icon btn-danger'><i class='icon-trash'></i></button>";
                                                    echo "</form>";
                                                    echo "</td></tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
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
        newwin = window.open('popup?demoid=' + demoid, 'windowname8', params);
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
        newwin = window.open('popup_vid?demoid=' + demoid + '&filename=' + filename, 'windowname5', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }
</script>