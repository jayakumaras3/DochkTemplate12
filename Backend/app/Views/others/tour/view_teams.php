<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments') ?>">Active Tournaments</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments/Scoreboard') ?>">SCOREBOARD</a></li>
                </ol>
            </div>
            <h4 class="page-title">Teams</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="row justify-content-between">
            <div class="col-auto">
            </div>
            <div class="col-auto">
                <form method="post" action="<?= base_url('Others/Tournaments/Add_New_Team') ?>">
                    <input type="hidden" name="tour_id" value="<?= $tour_id ?>">
                    <button type="submit" class="btn btn-danger rounded-pill waves-effect waves-light mb-2">
                        Add New Team
                    </button>
                </form>
            </div><!-- end col-->
        </div> <!-- end card -->
    </div> <!-- end col-->
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Team Name</th>
                            <th>Player 1</th>
                            <th>Player 2</th>
                            <th>Points</th>
                            <th>Edit</th>
                    </thead>

                    <tbody class="row_position">
                        <?php $sno = 0;
                        foreach ($all_teams as $teams):  $sno++; ?>
                            <tr>
                                <td><?= $sno; ?></td>
                                <td><?= $teams['team_name'] ?></td>
                                <td><?= $teams['player1'] ?></td>
                                <td><?= $teams['player2'] ?></td>
                                <td><?= $teams['points'] ?></td>
                                <td>
                                    <form method="post" action="<?= base_url('Others/Tournaments/Edit_Team') ?>">
                                        <input type="hidden" name="tt_id" value="<?= $teams['tt_id'] ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light">
                                            <span class="mdi mdi-pencil-outline"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>