<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more'); ?>">Course Detail</a></li>
    
                </ol>
            </div>
            <h4 class="page-title"> <?php echo $header; ?> </h4>
        </div>
    </div>
</div>
<div class="row">
    <?php $userlevel = session()->get('userlevel');
    $userlevelarray  = explode(',', $userlevel);
    $client = session()->get('client');
    $clientarrayid = explode(',', $client);
    $nxt_page = 1; ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!--                 <div class="row">


                    <div class="col-12" style="text-align: right;">
                        <span class="badge bg-danger rounded-pill ms-auto">&nbsp;&nbsp;&nbsp;</span> Not Started&nbsp;
                        <span class="badge bg-primary rounded-pill ms-auto">&nbsp;&nbsp;&nbsp;</span> Incomplete&nbsp;
                        <span class="badge bg-success rounded-pill ms-auto">&nbsp;&nbsp;&nbsp;</span> Competed
                    </div>
                </div> -->
                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th width=5%>#</th>
                            <th>Page Name</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Edit</th>
                    </thead>


                    <tbody class="row_position">
                        <?php $j = 0;

                        foreach ($pagesDetails as $eachpagesDetails) {
                            // print_r($eachCourseDetails);
                            // echo "<pre>";


                            if ($eachpagesDetails['type'] == 2) {
                                if (isset($eachpagesDetails['transcript']) && isset($eachpagesDetails['video']) && isset($eachpagesDetails['vtt'])) {
                                    $color = 'success';
                                } elseif (isset($eachpagesDetails['transcript']) || isset($eachpagesDetails['video']) || isset($eachpagesDetails['vtt'])) {
                                    $color = 'primary';
                                } else {
                                    $color = 'danger';
                                }
                            }
                            if ($eachpagesDetails['type'] == 1 || $eachpagesDetails['type'] == 3) {
                                if (isset($eachpagesDetails['folder'])) {
                                    $color = 'success';
                                } else {
                                    $color = 'dangar';
                                }
                            }
                            if ($eachpagesDetails['type'] == 4 || $eachpagesDetails['type'] == 5 || $eachpagesDetails['type'] == 6) {
                                if ($eachpagesDetails['question'] != '') {
                                    $color = 'success';
                                } else {
                                    $color = 'danger';
                                }
                            } ?>

                            <tr id="<?php echo $eachpagesDetails['page_id'] ?>">
                                <td>
                                    <?php echo $eachpagesDetails['page_number']; ?>
                                </td>
                                <td><?php echo $eachpagesDetails['page_name'] ?></td>
                                <td><?php $status = $eachpagesDetails['status'];

                                    switch ($status) {
                                        case 1:
                                            echo 'Editing';
                                            break;
                                        case 2:
                                            echo 'CE Rev';
                                            break;
                                        case 3:
                                            echo 'CE Fix';
                                            break;
                                        case 4:
                                            echo 'Client Rev';
                                            break;
                                        case 5:
                                            echo 'Client Fix';
                                            break;
                                        case 6:
                                            echo 'Final';
                                            break;
                                        case 11:
                                            echo 'Dev in Progress';
                                            break;
                                        case 12:
                                            echo 'Dev Completed';
                                            break;
                                        case 13:
                                            echo 'SME Reviewed';
                                            break;
                                        case 14:
                                            echo 'Lead Reviewed';
                                            break;
                                        case 15:
                                            echo 'QA Reviewed';
                                            break;
                                        case 16:
                                            echo 'Ready for Client Rev';
                                            break;
                                    }

                                    ?></td>
                                <?php if ($eachpagesDetails['type'] == 1) { ?>
                                    <td><?php echo 'Articulate'  ?></td>
                                <?php } elseif ($eachpagesDetails['type'] == 2) { ?>
                                    <td><?php echo 'Video' ?></td>
                                <?php } elseif ($eachpagesDetails['type'] == 3) { ?>
                                    <td><?php echo 'Html'  ?></td>
                                <?php } elseif ($eachpagesDetails['type'] == 4) { ?>
                                    <td><?php echo 'Quiz'  ?></td>
                                <?php } elseif ($eachpagesDetails['type'] == 5) { ?>
                                    <td><?php echo 'SCQ CYU'  ?></td>
                                    <?php } elseif ($eachpagesDetails['type'] == 8) { ?>
                                        <td><?php echo 'Video Sub Page'  ?></td>
                                <?php } elseif ($eachpagesDetails['type'] == 6) { ?>
                                    <td><?php echo 'MCQ CYU'  ?></td>
                                <?php } ?>



                                <?php if ($eachpagesDetails['type'] == 4) { ?>
                                    <!--                                                 <td>
                                                    <form class="form-horizontal" action="<?php echo base_url($assessment_link) ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                                        <input type="hidden" name="type" value="<?php echo $eachpagesDetails['type']; ?>">
                                                        <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                        <input type="hidden" name="page_name" value="<?php echo $eachpagesDetails['page_name'] ?>">
                                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="fe-settings"></span></button>
                                                    </form>
                                                </td> -->
                                    <?php } elseif ($eachpagesDetails['type'] == 5 || $eachpagesDetails['type'] == 6) {
                                    $key = array_search($eachpagesDetails['page_id'], array_column($questiondata, 'page_id'));
                                    if (!empty($key) || $key === 0) { ?>
                                        <!--                                                     <td>
                                                        <form class="form-horizontal" action="<?php echo base_url($edit_assessment_link) ?>" method="POST"><?= csrf_field() ?>
                                                            <input type="hidden" name="question_id" value="<?php echo $questiondata[$key]['question_id']; ?>">
                                                            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                                            <input type="hidden" name="type" value="<?php echo $eachpagesDetails['type']; ?>">
                                                            <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                            <input type="hidden" name="page_name" value="<?php echo $eachpagesDetails['page_name'] ?>">
                                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="fe-settings"></span></button>
                                                        </form>
                                                    </td> -->
                                    <?php  } else { ?>
                                        <!--                                                     <td>
                                                        <form class="form-horizontal" action="<?php echo base_url($add_assessment_link) ?>" method="POST"><?= csrf_field() ?>
                                                            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                                            <input type="hidden" name="type" value="<?php echo $eachpagesDetails['type']; ?>">
                                                            <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                            <input type="hidden" name="page_name" value="<?php echo $eachpagesDetails['page_name'] ?>">
                                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="fe-settings"></span></button>
                                                        </form>
                                                    </td> -->
                                    <?php }
                                } else { ?>
                                    <!--                                                 <td>
                                                    <form class="form-horizontal" action="<?php echo base_url($settings_link) ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                                        <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>"> 
                                                        <input type="hidden" name="page_name" value="<?php echo $eachpagesDetails['page_name'] ?>">
                                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="fe-settings"></span></button>
                                                    </form>
                                                </td> -->

                                <?php } ?>
                                <?php if ($eachpagesDetails['type'] == 5 || $eachpagesDetails['type'] == 6) {
                                    $key = array_search($eachpagesDetails['page_id'], array_column($questiondata, 'page_id'));
                                    if (!empty($key) || $key === 0) { ?>
                                        <td>
                                            <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/edit_quetion_view') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="question_id" value="<?php echo $questiondata[$key]['question_id']; ?>">
                                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                                <input type="hidden" name="type" value="<?php echo $eachpagesDetails['type']; ?>">
                                                <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                <input type="hidden" name="page_number" value="<?php echo $eachpagesDetails['page_number'] ?>">
                                                <input type="hidden" name="page_name" value="<?php echo $eachpagesDetails['page_name'] ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-square-edit-outline"></span></button>
                                            </form>
                                        </td>
                                    <?php
                                    }
                                } else { ?>
                                    <!-- Assessment/trainings/edit_quetion_view -->
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($edit_link) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="page_number" value="<?php echo $eachpagesDetails['page_number'] ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-square-edit-outline"></span></button>
                                        </form>
                                    </td>
                                <?php } ?>

                                <!--                                             <td>
                                                <form class="form-horizontal" action="<?php echo base_url($delete_link) ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="type" value="<?php echo  $eachpagesDetails['type']  ?>">
                                                    <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                                    <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                                </form>
                                            </td> -->
                            </tr>

                        <?php }
                        $j++;

                        if ($pagesDetails) {
                            $nxt_page = $eachpagesDetails['page_number'] + 1;
                        } else {
                            $nxt_page = 1;
                        }

                        ?>
                    </tbody>

                    <form action="<?php echo base_url($create_new_page_link) ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="nxt_pageid" value="<?php echo $nxt_page; ?>">
                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                        <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light mb-3"><i class="mdi mdi-plus"></i> Create New Page</button>
                    </form>
                    &nbsp;
                    <form action="#" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                        <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3"> Export Word</button>
                    </form>
                </table>
            </div>
        </div>
    </div>
</div>
</div>


<script type="text/javascript">
    var sortingAllowed = false; // Flag to track if sorting is allowed

    // Function to handle the mousedown event
    function handleDragStart(event) {
        if (!sortingAllowed) {
            // Show the confirmation dialog
            confirm("Do you want to drag?").then(function(response) {
                if (!response) {
                    event.preventDefault(); // Prevent the default dragging behavior if the user clicks "No"
                } else {
                    enableSorting(); // If the user clicks "Yes" or if sorting is already allowed, enable sorting
                }
            });
        } else {
            enableSorting(); // If sorting is already allowed, enable sorting
        }
    }

    // Function to enable sorting
    function enableSorting() {
        $(".row_position").sortable({
            delay: 150,
            stop: function(event, ui) {
                var selectedData = [];
                $('.row_position>tr').each(function() {
                    selectedData.push($(this).attr("id"));
                });
                console.log(selectedData);
                updateOrder(selectedData);
            }
        });
        sortingAllowed = true; // Update flag to indicate sorting is allowed
    }

    // Bind the custom event handler to the mousedown event on draggable elements
    $(".row_position").on("dragstart", "tr", handleDragStart);

    function updateOrder(data) {
        $.ajax({
            url: "<?php echo base_url('SCORM/course_builder/Scorm_course_pages/update_pagenumber'); ?>",
            type: 'post',
            data: {
                position: data
            },
            success: function() {
                // alert('Page number change successfully saved');
                location.reload();
            },
            error: function() {
                // Handle error
                console.error('Error found');
            }
        });
    }

    // Override the default confirm dialog with custom buttons
    window.confirm = function(message) {
        var confirmBox = $("#confirmBox");
        confirmBox.find(".message").text(message);
        return new Promise(function(resolve, reject) {
            confirmBox.modal("show");
            confirmBox.on("click", ".confirmYes", function() {
                resolve(true); // Resolve with true if "Yes" button is clicked
                confirmBox.modal("hide");
            });
            confirmBox.on("click", ".confirmNo", function() {
                resolve(false); // Resolve with false if "No" button is clicked
                confirmBox.modal("hide");
            });
        });
    };
</script>

<!-- Custom confirm dialog markup -->
<div id="confirmBox" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary confirmNo" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary confirmYes">Yes</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
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