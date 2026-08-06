<?php if (session()->get('error')) :
    echo '<script>alert("' . session()->get('error') . '")</script>';
endif;
$client =  session()->get('client');
$arraystakeholders  = explode(',', $client);

?>
<style>
    [data-tooltip]::before {
        position: absolute;
        content: attr(data-tooltip);
        font-size: 10px;
        opacity: 0;
        margin-top: 20px;
        width: 80px;
        background-color: #5A5A5A;
        color: #fff;
        text-align: center;
        border-radius: 3px;
        padding: 2px 0;
    }

    [data-tooltip]:hover::before {
        opacity: 1;
    }

    [data-tooltip]:not([data-tooltip-persistent])::before {
        pointer-events: none;
    }

    [data-tooltip1]::before {
        position: absolute;
        content: attr(data-tooltip1);
        font-size: 10px;
        opacity: 0;
        margin-top: -20px;
        width: 50px;
        background-color: #5A5A5A;
        color: #fff;
        text-align: center;
        border-radius: 3px;
        padding: 2px 0;
    }

    [data-tooltip1]:hover::before {
        opacity: 1;
    }

    [data-tooltip1]:not([data-tooltip1-persistent])::before {
        pointer-events: none;
    }
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>">Dashboard</a></li>
                    
                </ol>
            </div>
            <h4 class="page-title">My Reports</h4>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <p class="text-muted font-13 mb-4"></p>
            <?php
            if ($clientCourseddata != '') {
                if (count($clientCourseddata) > 0) {
                    $j = 0; ?>
                    <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>Course Name</th>
                                <th>Duration</th>
                                <th>Categories</th>
                                <th>Status</th>
                                <th>Details</th>
                                <!-- <th>Certificate</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientCourseddata as $clienteachCourseddata) {

                                $j = $j + 1; ?>
                                <tr>
                                    <td class="center"><?= $j ?></td>
                                    <td><?php echo $clienteachCourseddata['course_name'] ?></td>
                                    <td>
                                        <?php if ($clienteachCourseddata['duration'] > 0) { ?>
                                            <?php
                                            $duration = $clienteachCourseddata['duration'];
                                            if ($duration > 60) {
                                                $hours = intdiv($duration, 60);
                                                echo $hours . ' Hrs. ';
                                                $balancemin = $duration - $hours * 60;
                                                if ($balancemin > 0) {
                                                    echo $balancemin . ' min';
                                                }
                                            } else {
                                                echo $duration . ' min';
                                            }

                                            ?></br>
                                        <?php } ?>
                                    </td>
                                    <!-- <td>
                                        <?php
                                        if (strlen($clienteachCourseddata['language']) > 2) { ?>
                                            <?php echo $clienteachCourseddata['language'] ?>
                                        <?php } ?>
                                    </td> -->
                                    <td>
                                        <?php if (strlen($clienteachCourseddata['category']) > 2) { ?>
                                            <?php echo $clienteachCourseddata['category'] ?>
                                        <?php } ?>
                                    </td>
                                    <td> <?php if (strlen($clienteachCourseddata['lesson_status']) > 2) {
                                                if ($clienteachCourseddata['lesson_status'] == 'completed' || $clienteachCourseddata['lesson_status'] == 'passed') { ?>
                                                <span class="badge bg-soft-success text-success p-1"><?php echo ucfirst($clienteachCourseddata['lesson_status']) ?></span>
                                            <?php  } elseif ($clienteachCourseddata['lesson_status'] == 'incomplete') { ?>
                                                <span class="badge bg-soft-info text-info p-1"><?php echo 'In progress' ?></span>
                                            <?php } elseif ($clienteachCourseddata['lesson_status'] == 'not started') { ?>
                                                <span class="badge bg-soft-danger text-danger p-1"><?php echo 'Not Started' ?></span>
                                            <?php  } ?>

                                        <?php } else { ?>
                                            <span class="badge bg-soft-danger text-danger p-1"><?php echo 'Not Started'; ?></span>
                                        <?php } ?>
                                    </td>
                                    <!--                                     <td>
                                        <?php
                                        if (in_array('1', $arraystakeholders)) { // only TQ users access for Cart 
                                        ?>
                                            <div class=" btn-margin-custom">
                                                <a data-tooltip="Add To Cart" href="<?php echo base_url('Demo/cart/addToCart/' . $clienteachCourseddata['scourse_id']) ?>"><button class="btn btn-sm btn-warning"><i class="fa fa-shopping-cart"></i></button></a>
                                            </div>
                                        <?php }
                                        ?>
                                    </td> -->
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="crid" value="<?php echo $clienteachCourseddata['scourse_id'] ?>">
                                            <?php if ($clienteachCourseddata['demo'] == 1) {
                                                echo '<input type="hidden" name="detail_type" value="3">';
                                            } else {
                                                echo ' <input type="hidden" name="detail_type" value="2">';
                                            } ?>
                                             <input type="hidden" name="tab" value="1">
                                            <button class="btn btn-sm btn-success"><i class="fa fa-eye"></i></button>
                                        </form>
                                    </td>
                                    <!-- <td></td> -->
                                <?php
                            } ?>
                                </tr>
                        </tbody>
                    </table>


            <?php
                }
            }
            ?>
        </div>
    </div>
</div>




<script>
    // When the user clicks on div, open the popup
    var popuprev = "Empty";

    function myFunction(myPopup) {
        if (popuprev != "Empty") {
            var hidepop = document.getElementById(popuprev);
            hidepop.classList.remove('show');
        }
        if (popuprev != myPopup) {
            var popup = document.getElementById(myPopup);
            popup.classList.toggle("show");
        }
        popuprev = myPopup;
    }
    $('select').on('change', function() {

        var clientid = this.value;
        // console.log( this.value);
        document.cookie = 'clientid=' + this.value + ';path=/';
        // console.log(document.cookie);
        location.reload(true);

    });
</script>
<script type="text/javascript">
    function OpenNewWindow(MyPath) {
        window.open(MyPath, "", "toolbar=no,status=no,menubar=no,location=center,scrollbars=no,resizable=no,height=500,width=1024");
    }
</script>
<script>
    function submit(scormid) {
        console.log(scormid);
        document.getElementById(scormid).submit();

    }

    function OpenNewWindow(MyPath) {
        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        params += ', directories=no';
        params += ', location=no';
        params += ', menubar=no';
        params += ', resizable=no';
        params += ', scrollbars=no';
        params += ', status=no';
        params += ', toolbar=no';

        newwin = window.open(MyPath, "Launcher", params);
        var win_timer = setInterval(function() {
            if (newwin.closed) {
                window.location.reload();
                clearInterval(win_timer);
            }
        }, 100);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }
</script>