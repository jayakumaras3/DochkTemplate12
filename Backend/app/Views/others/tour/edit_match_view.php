<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments') ?>">Active Tournaments</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments/Scoreboard') ?>">SCOREBOARD</a></li>
                </ol>
            </div>
            <h4 class="page-title">Edit Match</h4>
        </div>
    </div>
</div>
<?php
$team2_name = '';
$team1_name = '';
?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Others/Tournaments/Update_Match_details') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Round Name</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="round" placeholder="" value="<?php echo $match_details['round']; ?>" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Team 1</label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="team_1" required>

                                <?php if ($match_details['team_1'] != 0) {
                                    echo '<option value="' . $match_details['team_1'] . '" SELECTED>' . $match_details['team_1_name'] . ' | ' . $match_details['t1_player_1_name'] . ' | ' . $match_details['t1_player_2_name'] . '</option>';
                                } else {
                                }
                                ?>
                                <?php foreach ($teams as $team) : ?>
                                    <option value="<?php echo $team['tt_id']; ?>" <?php if ($team['tt_id'] == $match_details['team_1']) {
                                                                                        //     echo 'selected';
                                                                                        $team1_name = $team['team_name'];
                                                                                    } ?>><?php echo $team['team_name'] . ' | ' . $team['player1'] . ' | ' . $team['player2']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Team 2</label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="team_2" required>
                                <?php if ($match_details['team_2'] != 0) {
                                    echo '<option SELECTED value="' . $match_details['team_2'] . '">' . $match_details['team_2_name'] . ' | ' . $match_details['t2_player_1_name'] . ' | ' . $match_details['t2_player_2_name'] . '</option>';
                                } else {
                                }
                                ?>
                                <?php foreach ($teams as $team) : ?>
                                    <option value="<?php echo $team['tt_id']; ?>" <?php if ($team['tt_id'] == $match_details['team_2']) {
                                                                                        //  echo 'selected';
                                                                                        $team2_name = $team['team_name'];
                                                                                    } ?>><?php echo $team['team_name'] . ' | ' . $team['player1'] . ' | ' . $team['player2']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Match date</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="match_date" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $match_details['match_date']; ?>">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Time Slot</label>
                        <div class="col-8 col-xl-9">
                            <input class="form-control" id="example-time" name="slot" type="time" value="<?php echo $match_details['slot']; ?>">
                        </div>
                    </div>
                    <input type="hidden" name="match_id" value="<?php echo $match_details['match_id']; ?>">
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-info btn-xs waves-effect waves-light">
                                Update Match Details
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <?php if ($match_details['who_won'] == 0) { ?>
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Others/Tournaments/Update_Match_Points') ?>" method="POST" id="submitForm"><?= csrf_field() ?>

                        <input type="hidden" name="who_won" value="<?php echo $match_details['team_1']; ?>">
                        <input type="hidden" name="match_id" value="<?php echo $match_details['match_id']; ?>">
                        <div class="justify-content-end row">
                            <div class="col-8 col-xl-9">
                                <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                    Team 1 | <?php echo $team1_name; ?> | Won the Match
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Others/Tournaments/Update_Match_Points') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                        <input type="hidden" name="who_won" value="<?php echo $match_details['team_2']; ?>">
                        <input type="hidden" name="match_id" value="<?php echo $match_details['match_id']; ?>">

                        <div class="justify-content-end row">
                            <div class="col-8 col-xl-9">
                                <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-primary btn-xs waves-effect waves-light">
                                    Team 2 | <?php echo $team2_name; ?> | Won the Match
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        <?php } else { ?>
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Others/Tournaments/Update_Match_Points') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                        <input type="hidden" name="who_won" value="0">
                        <input type="hidden" name="match_id" value="<?php echo $match_details['match_id']; ?>">

                        <div class="justify-content-end row">
                            <div class="col-8 col-xl-9">
                                <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                    Reset Match
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</div>