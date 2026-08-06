<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('Game/gamification'); ?>">
                            <?php echo lang('UI_Text.Gamification_Dashboard'); ?>
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                <?php echo lang('UI_Text.Assign_Game_Users'); ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Game/Gamification/assign_users'); ?>"
                    id="submitForm" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <label for="month"><?php echo lang('UI_Text.Assign_User'); ?></label>
                            <select class="form-select" name="id_user" required>
                                
                                <?php
                                foreach ($all_users as $users) {
                                    $key = array_search($users['id_user'], array_column($assigneduser, 'user_id'));
                                    if (!empty($key) || $key === 0) {
                                    } else {
                                        echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                    }
                                ?>


                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12 mt-1">
                            <input type="hidden" name="course_id" value="<?php echo $active_games[0]['course_id']; ?>">
                            <input type="hidden" class="form-control" name="game_id" value="<?php echo $game_id; ?>">
                            <Button type="submit" class=" btn btn-outline-primary btn-xs waves-effect waves-light"
                                id="submitButton"><?php echo lang('Buttons.Submit'); ?></Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php if (!empty($usergroupdata)) { ?>
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Game/Gamification/add_usergroup_to_game'); ?>"
                        id="submitForm" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-12 mb-1">
                                <label for="month"><?php echo lang('UI_Text.Assign_User_Group'); ?></label>
                                <select class="form-select" name="user_group_id" required>
                                  
                                    <?php
                                    if (isset($usergroupdata)) {
                                        foreach ($usergroupdata as $eachusergroupdata) {
                                            echo '<option value="' . $eachusergroupdata['sc_cgid'] . '">' . $eachusergroupdata['description'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-12 mt-1">
                                <input type="hidden" name="course_id" value="<?php echo $active_games[0]['course_id']; ?>">
                                <input type="hidden" class="form-control" name="game_id" value="<?php echo $game_id; ?>">
                                <button type="submit" id="submitButton"
                                    class=" btn btn-outline-primary btn-xs waves-effect waves-light"><?php echo lang('Buttons.Submit'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <table id="searchdatatable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th><?php echo lang('UI_Text.Employee'); ?></th>
                            <th><?php echo lang('UI_Text.Total_Attempts'); ?></th>
                            <th><?php echo lang('UI_Text.Score'); ?></th>
                            <th><?php echo lang('UI_Text.Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php // echo $game_id;
                        if (count($assigneduser) > 0) { ?>
                            <?php
                            $j = 0;

                            foreach ($assigneduser as $users) {
                                $j++;
                            ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $users['name'] . ' ' . $users['last_name']; ?></td>
                                    <td><?php echo $users['attempt']; ?></td>
                                    <td><?php echo $users['raw']; ?></td>
                                    <td>
                                        <form class="form-horizontal"
                                            action="<?php echo base_url('Game/Gamification/unassign_user'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="game_user_id"
                                                value="<?php echo $users['game_user_id']; ?>">
                                            <button class="btn btn-outline-danger waves-effect btn-xs waves-light"><span class="mdi mdi-stop-circle"></span> <?php echo lang('Buttons.Unassign'); ?></button>
                                        </form>
                                    </td>
                                </tr>
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