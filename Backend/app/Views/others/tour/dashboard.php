<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments/Add_Tournament') ?>">Add New Tournament</a></li>
                </ol>
            </div>
            <h4 class="page-title">Active Tournaments</h4>
        </div>
    </div>
</div>
<div class="row">
    <?php
    foreach ($all_tournaments as $tour):  ?>
        <div class="col-lg-4">
            <div class="card project-box">
                <div class="card-body">
                    <form method="post" action="<?= base_url('Others/Tournaments/Edit_Tournament') ?>">
                        <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
                        <button type="submit" class="btn btn-outline  waves-effect waves-light">
                            <h4><?= $tour['tournament_name'] ?></h4>
                        </button>
                    </form>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted text-uppercase">
                            <h5 class="m-0"> <span class="text-muted"> START DATE : </span> <small><?= $tour['start_date'] ?></small></h5>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted text-uppercase">
                            <h5 class="m-0"> <span class="text-muted"> END DATE : </span> <small><?= $tour['end_date'] ?></small></h5>
                            </p>
                        </div>
                    </div>

                    <div class="badge bg-soft-success text-success mb-1"><?php if ($tour['status'] == 1) {
                                                                                echo 'Active';
                                                                            } else {
                                                                                echo 'Editing';
                                                                            } ?></div>
                    <p class="mb-1">
                        <span class="pe-2   mb-2 d-inline-block">
                            <div class="row">


                                <div class="col-md-4">
                                    <form method="post" action="<?= base_url('Others/Tournaments/Scoreboard') ?>">
                                        <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
                                        <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">
                                            <?php echo $tour['total_matches']; ?> : Matches
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <form method="post" action="<?= base_url('Others/Tournaments/Scoreboard') ?>">
                                        <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
                                        <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
                                            <?php echo $tour['total_teams']; ?> : Teams
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <form method="post" action="<?= base_url('Others/Tournaments/Scoreboard') ?>">
                                        <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                            SCOREBOARD
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </span>
                    </p>
                </div>
            </div> <!-- end card box-->
        </div>
    <?php endforeach; ?>

</div>