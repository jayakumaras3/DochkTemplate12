<body class="bg-img-num1">
    <div class="container" style="padding: 40px;">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('demos/report') ?>">Report</a></li><b>&nbsp;>&nbsp;</b>
                    <li class="active">Report Schedule</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="clearfix">
                    <div class="pull-right tableTools-container"></div>
                </div>
                <div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 ">
                            <div class="x_panel">
                                <div class="x_content">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card-box table-responsive">
                                                <table id="datatable" class="table  table-sm table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                        <tr>
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
                                                        if ($getallcat) {

                                                            $conter = 1;
                                                            foreach ($getallcat as $eachallcat_vc) {

                                                                $demoid = $eachallcat_vc['demoid'];
                                                                $description = $eachallcat_vc['description'];
                                                                //print_r($demoid);
                                                                // exit();
                                                                $show = false;

                                                                echo "<tr>";
                                                                echo "<td>$conter</td>";
                                                                echo "<td>";

                                                                echo $eachallcat_vc['description'];

                                                                echo "</td><td>";
                                                                if (strlen($eachallcat_vc['casestudy']) > 2) {
                                                                    echo $eachallcat_vc['casestudy'];
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

                                                                    echo "<button type=\"submit\" class=\"btn btn-sm btn-success\" onClick=\"target_popup1('";
                                                                    echo $eachallcat_vc['vidx'];
                                                                    echo "')\">";
                                                                    echo '<i class="icon-play"></i>';
                                                                    echo '</button>';
                                                                }
                                                                echo "</td><td>";

                                                                if (strlen($eachallcat_vc['courselink']) > 2) {
                                                                    echo "<button type=\"submit\" class=\"btn btn-sm btn-success\" onClick=\"target_popup1('";
                                                                    echo $eachallcat_vc['courselink'];
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
                                                                echo "</tr>";

                                                                $conter = $conter + 1;
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
                                                <input type="hidden" name="demo_id" value="<?php echo $getallcat[0]['gdemoid']; ?>">
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
                                            <input type="hidden" name="user_uid" value="<?php echo $auid; ?>">
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


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
    var newpage;

    function target_popup1(url) {

        url = url.trim();

        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        $.post('demo_loader', {
            url: url
        }, function(result) {
            newpage = url;
            newwin = window.open('demo_loader', 'windowname5', params);

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
        $.post('demo_loader', {
            url: url
        }, function(result) {
            newpage = url;
            newwin = window.open('demo_loader', 'windowname5', params);

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
        $.post('demo_loader', {
            url: url
        }, function(result) {
            newpage = url;
            newwin = window.open('demo_loader', 'windowname8', params);

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
        $.post('demo_loader', {
            url: url
        }, function(result) {
            newpage = url;
            newwin = window.open('demo_loader', 'windowname5', params);

            if (window.focus) {
                newwin.focus();
            }
            return false;
        });
    }
</script>