<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments') ?>">Active Tournaments</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments/Scoreboard') ?>">SCOREBOARD</a></li>
                </ol>
            </div>
            <h4 class="page-title">Matches</h4>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Round</th>
                            <th>Team 1</th>
                            <th>Team 2</th>
                            <th>Match Date</th>
                            <th>Winner</th>
                            <th>Edit</th>
                    </thead>

                    <tbody class="row_position">
                        <?php $sno = 0;
                        foreach ($all_matches as $matches):  $sno++; ?>
                            <tr>
                                <td><?= $sno; ?></td>
                                <td><?= $matches['round'] ?></td>
                                <td><?= $matches['team_1'] ?></td>
                                <td><?= $matches['team_2'] ?></td>
                                <td><?= $matches['match_date'] ?></td>
                                <td><?= $matches['who_won'] ?></td>
                                <td>
                                    <form method="post" action="<?= base_url('Others/Tournaments/Edit_Match') ?>">
                                        <input type="hidden" name="match_id" value="<?= $matches['match_id'] ?>">
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