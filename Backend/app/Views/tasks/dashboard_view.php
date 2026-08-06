<?php $userlevel = session()->get('userlevel');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel));
// print_r($arrayuserlevel);
?>
<script>
    function close_window(feedbackid) {

        //$("#closereviewbtn").attr("disabled", true);
        $('#closereviewbtn').hide();
        $('#closereviewli').html('<span class="reviewmsg" style="color:red;" >Please wait... Submiting your feedback!</span>');

        $.ajax({
            url: '<?php echo base_url('review/adminProcess') ?>',
            type: 'POST',
            data: {
                reviewid: feedbackid,
                closetask: 1
            },
            success: function(response) {
                var obj = JSON.parse(response);
                //console.log(obj);
                $('#closereviewli').html('<span class="reviewmsg" style="color:red;" >Feedback Submitted Successfully! This window will close in 5 sec.</span> <span class="timeclass"></span>');
                location.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
                //console.log('request failed');
                //alert(xhr.responseText);
                console.log('request failed ' + xhr.responseText);
                if (xhr.status == 404) {
                    $('#reviewmsg').text(+xhr.responseText);
                }
                if (xhr.status == 502) {
                    $('#reviewmsg').text(+xhr.responseText);
                }
                if (xhr.status == 504) {
                    $('#reviewmsg').text(+xhr.responseText);
                }
                $('#closereviewbtn').show();

            }
        });

    }

    function closereview(feedbackid) {
        if (confirm("Are you sure you have captured all your feedback?\n\nIf Yes, click OK.\n\nOnce you click OK, you cannot make any further changes.")) {
            close_window(feedbackid);
        }
    }
</script>
<div class="row"> 
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>My</li><b>&nbsp;>&nbsp;</b>
            <li class="active">Tasks</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if (session()->get('error')) :
            echo '<script>alert("' . session()->get('error') . '")</script>';
        endif; ?>
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="head bg-default bg-light-ltr">
                    <table id="datatable-fixed-header" class="table  table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>Project Name</th>
                                <th>Description</th>
                                <th>Stage</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Details</th>
                                <th>Complete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            if ($getmyassignment != '') {
                                if (count($getmyassignment) > 0) {
                                    foreach ($getmyassignment as $myeachassignment) {
                                        $j = $j + 1; ?>
                                        <tr>
                                            <td><?php echo $j ?></td>
                                            <td><?php echo $myeachassignment['projectname'] . ' - ' . $myeachassignment['course_name'] ?></td>
                                            <td><?php echo $myeachassignment['description'] ?><br /><br />
                                                <?php if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { // access given TQ project managr, super admin and client admin
                                                    echo isset($myeachassignment['event_description']) ? $myeachassignment['event_description'] . ' - ' . $myeachassignment['username'] : '';
                                                }  ?>
                                            </td>
                                            <td><?php echo  $myeachassignment['stage'] ?><br /><br />
                                                <?php if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
                                                    echo  $myeachassignment['level'];
                                                } ?>
                                            </td>
                                            <td><?php switch ($myeachassignment['crstatus']) {
                                                    case 1:
                                                        echo 'Assigned';
                                                        break;
                                                    case 2:
                                                        echo 'Reviewed';
                                                        break;
                                                    case 3:
                                                        echo 'Deleted';
                                                        break;
                                                } ?>
                                            </td>
                                            <td><?php echo  date('m-d-Y', strtotime($myeachassignment['duedate'])) ?><br /><br />
                                                <?php if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
                                                    echo  isset($myeachassignment['max_date']) ? date('m-d-Y', $myeachassignment['max_date']) : '';
                                                } ?>
                                            </td>
                                            <td><?php $btntype = '';
                                                if ($myeachassignment['coursetype'] == 21 ||  $myeachassignment['coursetype'] == 62) {
                                                    $btntype = 'btn-success';
                                                } elseif ($myeachassignment['coursetype'] == 22) {
                                                    $btntype = 'btn-primary';
                                                }
                                                if ($myeachassignment['projectTheme'] == 'GE') {
                                                    echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon ' . $btntype . '"onclick="popup(' . $myeachassignment['reviewid'] . ',' . $myeachassignment['courseid'] . ',1,' . $myeachassignment['coursetype'] . ')">';
                                                } elseif ($myeachassignment['projectTheme'] == 'VR') {
                                                    echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon ' . $btntype . '"onclick="popup(' . $myeachassignment['reviewid'] . ',' . $myeachassignment['course_id'] . ',' . $myeachassignment['pageid'] . ',' . $myeachassignment['coursetype'] . ')">';
                                                } else {
                                                    if ($myeachassignment['coursetype'] == 21 || $myeachassignment['coursetype'] == 22 || $myeachassignment['coursetype'] == 24) {
                                                        echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon ' . $btntype . '"onclick="popup(' . $myeachassignment['reviewid'] . ',' . $myeachassignment['courseid'] . ',1,' . $myeachassignment['coursetype'] . ')">';
                                                    } else {
                                                        echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon  btn-info" onclick="popup(' . $myeachassignment['reviewid'] . ',' . $myeachassignment['courseid'] . ',1,23)">';
                                                    }
                                                }
                                                echo '<span class="icon-play"></span>';
                                                echo '</a>'; ?>
                                            </td>
                                            <td><a href="javascript: void(0)" type="button" id="closereviewbtn" title="Close Review" onclick="closereview('<?= $myeachassignment['reviewid']; ?>')" class="btn btn-sm widget-icon btn-info">
                                                    <span class="fa fa-check"></span></a></td>
                                        </tr>
                            <?php }
                                }
                            } ?>
                            <?php $j = 0;
                            foreach ($getmyassignmentpage as $myeachassignment) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo  $j ?></td>
                                    <td><?php echo  $myeachassignment['projectname'] . ' - ' . $myeachassignment['course_name'] . ' - S' . $myeachassignment['sequence'] . ' - ' . $myeachassignment['pagename'] ?><br />
                                        <?php if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { // access given TQ project managr, super admin and client admin
                                            echo isset($myeachassignment['event_description']) ? $myeachassignment['event_description'] . ' - ' . $myeachassignment['username'] : '';
                                        }  ?> </td>
                                    <td><?php echo  $myeachassignment['stage'] ?><br /><br />
                                        <?php if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { // access given TQ project managr, super admin and client admin
                                            echo  $myeachassignment['level'];
                                        } ?> </td>
                                    <td><?php echo  date('m-d-Y', strtotime($myeachassignment['duedate'])) ?><br /><br />
                                        <?php if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { // access given TQ project managr, super admin and client admin
                                            echo  isset($myeachassignment['max_date']) ? date('m-d-Y', $myeachassignment['max_date']) : '';
                                        } ?></td>
                                    <?php if ($myeachassignment['coursetype'] == 21) {
                                        $btntype = 'btn-success';
                                    } else if ($myeachassignment['coursetype'] == 22) {
                                        $btntype = 'btn-primary';
                                    } ?>
                                    <td> <?php echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon ' . $btntype . '"  onclick="popup(' . $myeachassignment['reviewid'] . ', ' . $myeachassignment['course_id'] . ',' . $myeachassignment['pageid'] . ',22)">';
                                            echo '<span class="icon-play"></span>';
                                            echo '</a>'; ?>
                                    </td>
                                    <td><a href="javascript: void(0)" type="button" id="closereviewbtn" title="Close Review" onclick="closereview('<?= $myeachassignment['reviewid']; ?>')" class="btn btn-sm widget-icon btn-info">
                                            <span class="fa fa-check"></span></a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="pull-right">
    <a href="<?php echo base_url('tasks/task_closed_view') ?>" type="button" class="btn btn-sm widget-icon btn-danger">Closed Tasks View</a>
</div>
<!-- inline scripts related to this page -->
<script type="text/javascript">
    jQuery(function($) {
        $('#dynamic-table1').DataTable();
        $('#dynamic-table2').DataTable();
        $('#dynamic-table3').DataTable();
        $('#dynamic-table4').DataTable();
        $('#dynamic-table5').DataTable();
        //initiate dataTables plugin

        $('#errormeg').hide();
        $('#docheckfeedbackForm').on('submit', function(event) {
            event.preventDefault();
            console.log('docheckfeedbackForm Clicked');

            //dataString = $("#addcharForm").serialize();
            var dataString = new FormData($('#docheckfeedbackForm')[0]);

            if (typeof FormData !== 'undefined') {

                $.ajax({
                    url: '<?php echo base_url('dashboard/docheckfeedsubmit') ?>',
                    type: "POST",
                    data: dataString,
                    processData: false,
                    contentType: false,
                    success: function(data) {

                        var obj = JSON.parse(data);

                        if (obj.status == 'OK') {
                            console.log(obj.status);
                            $('#errormeg').show();
                            $('#errormeg').text('Suggestion Submitted!!!');
                            setInterval(function() {
                                $('#errormeg').hide();
                            }, 5000);
                            $('#docheckfeedback').val('');
                        } else {
                            console.log(obj.status);
                            $('#errormeg').show();
                            $('#errormeg').text(obj.status);
                        }


                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log('request failed ' + xhr.status);
                        if (xhr.status == 404) {

                            $('#errormeg').show();
                            $('#errormeg').text('404: Page not found. Please contact admin.');

                        }
                        if (xhr.status == 502) {

                            $('#errormeg').show();
                            $('#errormeg').text('502: Comment not saved. Please Refresh this page & save again.');

                        }
                        if (xhr.status == 504) {

                            $('#errormeg').show();
                            $('#errormeg').text('504: Comment not saved. Please Refresh this page & save again.');

                        }
                    }
                })
            } else {
                message("Your Browser Don't support FormData API! Use IE 10 or Above!");
            }

        });

    });
</script>
<script>
    $(document).ready(function() {
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
</script>