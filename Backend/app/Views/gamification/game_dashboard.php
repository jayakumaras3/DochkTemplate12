<?php
$userlevel = session('userlevel');
$client = session('client');
$arrayuserlevel = array_map('intval', explode(',', $userlevel));
?>
<style>
    .thumbnail_db img {
        height: 100%;
        width: 100%;
    }

    .thumbnail_db img {
        object-fit: contain;
    }

    /* Add these styles for stars */
    .star {
        font-size: 24px;
        color: #ccc;
        cursor: pointer;
    }

    .star.selected,
    .star.hover,
    .star.half-selected {
        color: #ffcc00;
    }

    .star-rating {
        /* font-size: 24px; */
        color: gold;
        /* Adjust the color as needed */
    }

    .rating-container {
        display: inline-flex;
        /* Use inline-flex for better control */
        align-items: center;
        /* Align items vertically in the flex container */
    }

    #ratingContainer {
        /* Adjust the styles as needed */
        font-size: 18px;
        /* Example font size */
        margin-left: 0;
        /* Adjust as needed for spacing between rating and count user */
        line-height: 1;
        /* Reset the line-height to default for precise vertical alignment */
    }

    .cardtile {
        padding: 10px;
        background-color: rgba(255, 255, 255, 1);
        margin-right: 1px;
        margin-left: 1px;
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.02);
    }

    .cardshaddow {
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.02);
    }
</style>
<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');


?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <?php if (in_array('2010', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { ?>
                <div class="page-title-right">
                    <form action="<?php echo base_url('Game/Gamification/create_new_game'); ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="cid" value="MQ==">
                        <button type="button"
                            class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light float-end"
                            data-bs-toggle="modal"
                            data-bs-target="#createGameModal">
                            <i class="mdi mdi-plus-circle"></i> <?php echo lang('Buttons.Create_New_Game'); ?>
                        </button>

                    </form>
                </div>
            <?php } ?>
            <h4 class="page-title">
                <?php echo lang('UI_Text.Gamification_Dashboard'); ?>
            </h4>
        </div>
    </div>
</div>
<div class="modal fade" id="createGameModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-l modal-dialog-centered modal-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?php echo lang('Buttons.Create_New_Game'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form action="<?php echo base_url('Game/Gamification/addGame'); ?>"
                    method="POST" id="submitForm"><?= csrf_field() ?>

                    <div class="row">
                        <div class="col-12 mb-2">
                            <label><?php echo lang('UI_Text.Game_Name'); ?></label>
                            <input type="text" class="form-control" name="game_name" required>
                        </div>

                        <div class="col-12 mb-2">
                            <label><?php echo lang('UI_Text.Select_Course'); ?></label>
                            <select class="form-select" name="course_id" required>
                                
                                <?php foreach ($clientCourseddata as $courses) { ?>
                                    <option value="<?php echo $courses['scourse_id']; ?>">
                                        <?php echo $courses['course_name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-12 mb-2">
                            <label><?php echo lang('UI_Text.Start_Date'); ?></label>
                            <input name="start_on" class="form-control start-date" type="date" required>
                        </div>

                        <div class="col-12 mb-2">
                            <label><?php echo lang('UI_Text.End_Date'); ?></label>
                            <input name="end_on" class="form-control end-date" type="date" required>
                        </div>

                    </div>

                    <div class="col-12 mb-2">
                        <label><?php echo lang('UI_Text.Attempts_Allowed'); ?></label>
                        <input type="number" class="form-control" name="attempts" min="0">
                    </div>





                    <div class="text-center mt-3">
                        <!-- <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button> -->
                        <button type="submit"
                            class="btn btn-outline-primary btn-xs waves-effect waves-light"
                            id="submitButton">
                            <?php echo lang('Buttons.Submit'); ?>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?php if ($client == 1) { ?>
<!--     <div id="holder" class="row row-cols-1 row-cols-md-3 g-3 ">
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body" style="text-align:left; ">
                    <form class="form-horizontal" action="<?php echo base_url('Others/Tour_user') ?>" method="POST"><?= csrf_field() ?>
                        <button
                            style="border: none; border:0; padding-top: 5px; outline: none; background: none; width: 100%; text-align:center">
                            <div style="display: box;
  display: flex;
  box-align: center;
  align-items: center;
  box-pack: center;
  justify-content: center;">
                                <img class="img-fluid mx-auto d-block rounded" src="<?php echo base_url('assets/assets/img/tour_main.jpg'); ?>"
                                    style="border: 1px solid transparent; display: block;background: none;  border-color: rgb(0, 0, 0, 0.2);  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);  height:150px;"
                                    alt="">

                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
<?php } ?>
<?php
if (count($course_details) > 0) {
    foreach ($course_details as $key => $clienteachCourseddata) {

        if (isset($clienteachCourseddata['thumbnail']) && $clienteachCourseddata['thumbnail'] != '') {
            $thumbnail = base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $clienteachCourseddata['scourse_id'] . '/' . $clienteachCourseddata['thumbnail']);
        } else {
            $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
        }
?>
        <?php if ($clienteachCourseddata['status'] == 5) { ?>
            <div id="holder" class="row row-cols-1 row-cols-md-3 g-3 ">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body" style="text-align:left; ">
                            <form class="form-horizontal" action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="crid" value="<?php echo $clienteachCourseddata['scourse_id'] ?>">
                                <input type="hidden" name="detail_type" value="4">
                                <input type="hidden" name="tab" value="1">
                                <button
                                    style="border: none; border:0; padding-top: 5px; outline: none; background: none; width: 100%; text-align:center">
                                    <div style="display: box;
  display: flex;
  box-align: center;
  align-items: center;
  box-pack: center;
  justify-content: center;">
                                        <img class="img-fluid mx-auto d-block rounded" src="<?= $thumbnail ?>"
                                            style="border: 1px solid transparent; display: block;background: none;  border-color: rgb(0, 0, 0, 0.2);  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);  height:150px;"
                                            alt="">
                                        <img style=" 
                                height: 40px;
                                width: 40px;
                                position: absolute;
                                opacity: 0.5;" src="<?php echo base_url('assets/assets/img/play.png'); ?>" alt="play"
                                            class="playBtn">
                                    </div>
                                </button>
                            </form>
                            <div style="padding-left: 10px;">
                                <h5 class="font-12 my-1 sp-line-1"><a class="text-dark"
                                        title=""><?php echo $clienteachCourseddata['course_name']; ?></a> </h5>
                                <span class="font-10 text-muted"> <?php echo lang('UI_Text.Duration'); ?> : <?php echo $clienteachCourseddata['duration']; ?>
                                    min</span>
                                <br /> <span class="font-10 text-muted"> <?php echo lang('UI_Text.Start_On'); ?> : <?php echo $clienteachCourseddata['start_on']; ?></span>
                                    <?php echo $clienteachCourseddata['start_on']; ?></span>
                                <br /> <span class="font-10 text-muted"> <?php echo lang('UI_Text.Ends_On'); ?> : <?php echo $clienteachCourseddata['end_on']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if ($clienteachCourseddata['status'] == 2) { ?>
                <div class="col-xl-12">
                <?php } else { ?>
                    <div class="col-xl-8">
                    <?php } ?>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3"><?php echo lang('UI_Text.Leaderboard'); ?> - <?php echo $clienteachCourseddata['course_name']; ?>
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-borderless  table-hover table-centered m-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th><?php echo lang('UI_Text.Position'); ?></th>
                                            <th><?php echo lang('UI_Text.Player_name'); ?></th>
                                            <th><?php echo lang('UI_Text.Total_Attempts'); ?></th>
                                            <th><?php echo lang('UI_Text.Score'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j = 0;
                                        if (!empty($usersGameList)) {
                                            foreach ($usersGameList as $user) {
                                                if ($clienteachCourseddata['scourse_id'] == $user['course_id']) {
                                                    $j = $j + 1; ?>
                                                    <tr>
                                                        <td>
                                                            <h5 class="m-0 fw-normal"><?php echo $j ?></h5>
                                                        </td>
                                                        <td>
                                                            <?php echo $user['name'] . ' ' . $user['last_name'] ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $user['attempt'] ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-soft-warning text-success"><b>
                                                                    <?php echo $user['raw'] ?></b></span>
                                                        </td>
                                                    </tr>
                                        <?php }
                                            }
                                        } ?>
                                        <!-- <tr>
                                        <td>
                                            <h5 class="m-0 fw-normal">1</h5>
                                        </td>
                                        <td>
                                            Pramod Chandran
                                        </td>
                                        <td>
                                            5
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-warning text-success"><b>99</b></span>
                                        </td>
                                    </tr> -->
                                    </tbody>
                                </table>
                            </div> <!-- end .table-responsive-->
                        </div>
                    </div> <!-- end card-->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3"><?php echo $clienteachCourseddata['course_name']; ?></h4>
                            <div class="table-responsive">
                                <table class="table table-borderless  table-hover table-centered m-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th><?php echo lang('UI_Text.Player_name'); ?></th>
                                            <th><?php echo lang('UI_Text.Total_Attempts'); ?></th>
                                            <th><?php echo lang('UI_Text.Score'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j = 0;

                                        $hasScore = false;

                                        if (!empty($get_user_games_report)) {
                                            foreach ($get_user_games_report as $user) {
                                                if (session()->get('id_user') == $user['user_id'] && $clienteachCourseddata['scourse_id'] == $user['course_id']) {
                                                    $hasScore = true;
                                                    $j = $j + 1;
                                        ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $user['name'] . ' ' . $user['last_name'] ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $user['attempt'] ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-soft-warning text-success"><b>
                                                                    <?php echo $user['raw'] ?></b></span>
                                                        </td>
                                                    </tr>
                                        <?php
                                                }
                                            }

                                            if (!$hasScore) {
                                                echo "<tr><td colspan='3'><span class='text-info'>You haven't completed this game yet. Start now to earn your score!!</span></td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3'><span class='text-muted'>Waiting for score...</span></td></tr>";
                                        }
                                        ?>


                                    </tbody>
                                </table>
                            </div> <!-- end .table-responsive-->
                        </div>
                    </div> <!-- end card-->

                    </div>

                </div>
            <?php
        }
    } else { ?>
            <div class="row">
                <div id="alertdiv" class="floaterwindow_red">
                    <?php echo lang('Statements.State_0007'); ?>
                    <div id="progressBar"></div>
                </div>
            </div>
        <?php } ?>

        <?php if (in_array('2010', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { ?>
            <div id="holder" class="row row-cols-1 row-cols-md-3 g-3 ">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th><?php echo lang('UI_Text.Game_Name'); ?></th>
                                        <th><?php echo lang('UI_Text.Course_Name'); ?></th>
                                        <th><?php echo lang('UI_Text.Attempts'); ?></th>
                                        <th><?php echo lang('UI_Text.Start_On'); ?></th>
                                        <th><?php echo lang('UI_Text.End_On'); ?></th>
                                        <th><?php echo lang('UI_Text.Status'); ?></th>
                                        <th><?php echo lang('UI_Text.Users'); ?></th>
                                        <th><?php echo lang('UI_Text.Edit'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($active_games) > 0) { ?>
                                        <?php
                                        $j = 0;
                                        foreach ($active_games as $games) {
                                            $j++;
                                        ?>
                                            <tr>
                                                <td><?php echo $j; ?></td>
                                                <td><?php echo $games['game_name']; ?></td>
                                                <td><?php echo $games['course_name']; ?></td>
                                                <td><?php if ($games['attempts'] == 0) {
                                                        echo "Unlimited";
                                                    } else {
                                                        echo $games['attempts'];
                                                    } ?></td>
                                                <td><?php echo $games['start_on']; ?></td>
                                                <td><?php echo $games['end_on']; ?></td>
                                                <td><?php switch ($games['status']) {
                                                        case 1:
                                                            echo lang('UI_Text.Editing');
                                                            break;
                                                        case 2:
                                                            echo lang('UI_Text.Game_Ended');
                                                            break;
                                                        case 5:
                                                            echo lang('UI_Text.Live');
                                                            break;
                                                        default:
                                                            echo lang('UI_Text.Game_Ended');
                                                            break;
                                                    } ?></td>

                                                <td>
                                                    <form class="form-horizontal"
                                                        action="<?php echo base_url('Game/Gamification/game_users'); ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="game_id" value="<?php echo $games['game_id']; ?>">
                                                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span
                                                                class="mdi mdi-account-group-outline"></span> <?php echo lang('Buttons.Assign_Users'); ?></button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form class="form-horizontal"
                                                        action="<?php echo base_url('Game/Gamification/view_details'); ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="game_id" value="<?php echo $games['game_id']; ?>">
                                                        <button type="button"
                                                            class="btn btn-outline-warning btn-xs waves-effect waves-light"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editGameModal_<?php echo $games['game_id']; ?>">
                                                            <span class="mdi mdi-pencil-outline"></span> <?php echo lang('Buttons.Edit'); ?>
                                                        </button>


                                                    </form>

                                                </td>
                                            </tr>
                                            <div class="modal fade"
                                                id="editGameModal_<?php echo $games['game_id']; ?>"
                                                tabindex="-1"
                                                aria-hidden="true">

                                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">

                                                        <!-- Header -->
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"><?php echo lang('UI_Text.Edit_Game'); ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <!-- Body -->
                                                        <div class="modal-body">
                                                            <form action="<?php echo base_url('Game/Gamification/editGame'); ?>"
                                                                method="POST"><?= csrf_field() ?>

                                                                <div class="row">

                                                                    <div class="col-md-12 mb-2">
                                                                        <label><?php echo lang('UI_Text.Game_Name'); ?></label>
                                                                        <input type="text" class="form-control"
                                                                            name="game_name"
                                                                            value="<?php echo $games['game_name']; ?>" required>
                                                                    </div>

                                                                    <div class="col-md-12 mb-2">
                                                                        <label><?php echo lang('UI_Text.Select_Course'); ?></label>
                                                                        <select class="form-select" name="course_id" required>
                                                                            <?php foreach ($clientCourseddata as $courses) { ?>
                                                                                <option value="<?php echo $courses['scourse_id']; ?>"
                                                                                    <?php if ($games['course_id'] == $courses['scourse_id']) echo "selected"; ?>>
                                                                                    <?php echo $courses['course_name']; ?>
                                                                                </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-12 mb-2">
                                                                        <label><?php echo lang('UI_Text.Start_Date'); ?></label>
                                                                        <input name="start_on" class="form-control start-date" type="date"
                                                                            value="<?php echo $games['start_on']; ?>" required>
                                                                    </div>

                                                                    <div class="col-md-12 mb-2">
                                                                        <label><?php echo lang('UI_Text.End_Date'); ?></label>
                                                                        <input name="end_on"
                                                                            class="form-control end-date"
                                                                            type="date"
                                                                            value="<?php echo $games['end_on']; ?>"
                                                                            min="<?php echo $games['start_on']; ?>"
                                                                            required>

                                                                    </div>

                                                                    <div class="col-md-12 mb-2">
                                                                        <label><?php echo lang('UI_Text.Attempts_Allowed'); ?></label>
                                                                        <input type="number" class="form-control"
                                                                            name="attempts" min="0"
                                                                            value="<?php echo $games['attempts']; ?>">
                                                                    </div>

                                                                    <div class="col-md-12 mb-2">
                                                                        <label><?php echo lang('UI_Text.Status'); ?></label>
                                                                        <select class="form-select" name="status">
                                                                            <option value="1" <?php if ($games['status'] == 1) echo "selected"; ?>><?php echo lang('UI_Text.Editing'); ?></option>
                                                                            <option value="5" <?php if ($games['status'] == 5) echo "selected"; ?>><?php echo lang('UI_Text.Live'); ?></option>
                                                                            <option value="2" <?php if ($games['status'] == 3) echo "selected"; ?>><?php echo lang('UI_Text.Game_Ended'); ?></option>
                                                                            <option value="0" <?php if ($games['status'] == 0) echo "selected"; ?>><?php echo lang('UI_Text.Delete'); ?></option>
                                                                        </select>
                                                                    </div>

                                                                </div>


                                                                <input type="hidden" name="game_id"
                                                                    value="<?php echo $games['game_id']; ?>">

                                                                <!-- Footer -->
                                                                <div class="text-center">
                                                                    <button type="submit"
                                                                        class="btn btn-outline-warning btn-xs waves-effect waves-ligh">
                                                                        <?php echo lang('Buttons.Update'); ?>
                                                                    </button>
                                                                </div>

                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        <?php } ?>
        <script>
            document.querySelectorAll('.modal').forEach(function(modal) {

                modal.addEventListener('shown.bs.modal', function() {

                    const startInput = modal.querySelector('.start-date');
                    const endInput = modal.querySelector('.end-date');

                    if (!startInput || !endInput) return;

                    // Set min end date on open (EDIT case)
                    if (startInput.value) {
                        endInput.min = startInput.value;
                    }

                    // When start date changes
                    startInput.addEventListener('change', function() {
                        if (!this.value) return;

                        endInput.min = this.value;

                        if (endInput.value && endInput.value < this.value) {
                            endInput.value = '';
                        }
                    });
                });
            });
        </script>