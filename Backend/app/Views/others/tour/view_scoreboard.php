<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments') ?>">Active Tournaments</a></li>
                </ol>
            </div>
            <h4 class="page-title">SCOREBOARD</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                            <i class="mdi mdi-gamepad-variant-outline font-22 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo count($all_matches); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Matches</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                            <i class="mdi mdi-account-group-outline font-22 avatar-title text-success"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo count($all_teams); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Teams</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                            <i class="mdi mdi-clock-outline font-22 avatar-title text-info"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><?php echo $tournament_details['start_date']; ?></h3>
                            <p class="text-muted mb-1 text-truncate">Start Date</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                            <i class="mdi mdi-clock-time-nine font-22 avatar-title text-warning"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><?php echo $tournament_details['end_date']; ?></h3>
                            <p class="text-muted mb-1 text-truncate">Final Date</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
</div>
<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-between mb-2">
                    <div class="col-auto">
                        <form>
                            <div class="mb-2">
                                <h4>MATCHES</h4>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end">
                            <form method="post" action="<?= base_url('Others/Tournaments/Add_New_Match') ?>">
                                <input type="hidden" name="tour_id" value="<?= $tour_id ?>">
                                <button type="submit" class="btn btn-warning  btn-xs waves-effect waves-light mb-2 me-1">
                                    Add New Match
                                </button>
                            </form>
                        </div>
                    </div><!-- end col-->
                </div>

                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Round</th>
                            <th>Team 1</th>
                            <th>Team 2</th>
                            <th>Match Date</th>
                            <th>Winner</th>

                    </thead>

                    <tbody class="row_position">
                        <?php $sno = 0;
                        foreach ($all_matches as $matches):  $sno++; ?>
                            <tr>
                                <td>
                                    <form method="post" action="<?= base_url('Others/Tournaments/Edit_Match') ?>">
                                        <input type="hidden" name="match_id" value="<?= $matches['match_id'] ?>">
                                        <button type="submit" class="btn btn-xs btn-outline-secondary">
                                            <?= $sno; ?>
                                        </button>
                                    </form>
                                </td>
                                <td><?= $matches['round'] ?></td>
                                <td><?= $matches['team_1'] ?></td>
                                <td><?= $matches['team_2'] ?></td>
                                <td><?= $matches['match_date'] ?> | <?= $matches['slot'] ?></td>
                                <td><?= $matches['who_won'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-between mb-2">
                    <div class="col-auto">
                        <form>
                            <div class="mb-2">

                                <h4>TEAMS</h4>

                            </div>
                        </form>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end">
                            <form method="post" action="<?= base_url('Others/Tournaments/Add_New_Team') ?>">
                                <input type="hidden" name="tour_id" value="<?= $tour_id ?>">
                                <button type="submit" class="btn btn-danger btn-xs waves-effect waves-light mb-2 me-1">
                                    Add New Team
                                </button>
                            </form>
                        </div>
                    </div><!-- end col-->
                </div>
                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Team Name</th>
                            <th>Player 1</th>
                            <th>Player 2</th>
                            <th>Wins</th>
                    </thead>

                    <tbody class="row_position">
                        <?php $sno = 0;
                        foreach ($all_teams as $teams):  $sno++; ?>
                            <tr>
                                <td>
                                    <form method="post" action="<?= base_url('Others/Tournaments/Edit_Team') ?>">
                                        <input type="hidden" name="tt_id" value="<?= $teams['tt_id'] ?>">
                                        <button type="submit" class="btn btn-xs btn-outline-primary">
                                            <?= $sno; ?>
                                        </button>
                                    </form>
                                </td>
                                <td><?= $teams['team_name'] ?></td>
                                <td><?= $teams['player1'] ?></td>
                                <td><?= $teams['player2'] ?></td>
                                <td><?= $teams['total_wins'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>