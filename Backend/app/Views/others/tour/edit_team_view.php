<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments') ?>">Active Tournaments</a></li>
                       <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments/Scoreboard') ?>">SCOREBOARD</a></li>
             
                </ol>
            </div>
            <h4 class="page-title">Edit Team Details</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Others/Tournaments/Update_Team') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Team Name</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="team_name" placeholder="Tournament Name" value="<?php echo $team_details['team_name'] ?>" required />

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Player 1</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="player1" placeholder="Player 1 Name" value="<?php echo $team_details['player1'] ?>" required />

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Player 2</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="player2" placeholder="Player 2 Name" value="<?php echo $team_details['player2'] ?>" required />

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Points</label>
                        <div class="col-8 col-xl-9">
                            <input type="number" class="form-control" name="points" placeholder="Points" value="<?php echo $team_details['points'] ?>" required />

                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Status</label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="status" required>
                                <option value="1" <?php if ($team_details['status'] == 1) echo 'selected'; ?>>Active</option>
                                <option value="0" <?php if ($team_details['status'] == 0) echo 'selected'; ?>>Delete</option>
                            </select>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <input type="hidden" name="tt_id" value="<?php echo $team_details['tt_id'] ?>">
                        <div class="col-8 col-xl-9">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-info btn-xs waves-effect waves-light">
                                Update Teams
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Others/Tournaments/Add_Member_to_Team') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Team Member</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="username" placeholder="Name" value="" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Team Role</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="user_role" placeholder="Role" value="" required />
                        </div>
                    </div>

                    <div class="justify-content-end row">
                        <input type="hidden" name="tt_id" value="<?php echo $team_details['tt_id'] ?>">
                        <div class="col-8 col-xl-9">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Add Team Member
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Player</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $sno = 0;
                        foreach ($team_members as $members):  $sno++; ?>
                            <tr>
                                <td><?= $sno; ?></td>
                                <td><?= $members['username'] ?></td>
                                <td><?= $members['user_role'] ?></td>
                                <td>
                                    <form method="post" action="<?= base_url('Others/Tournaments/Delete_team_member') ?>">
                                        <input type="hidden" name="tu_id" value="<?= $members['tu_id'] ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                            <span class="mdi mdi-trash-can-outline"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->
</div>