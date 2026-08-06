<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments') ?>">Active Tournaments</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments/Scoreboard') ?>">SCOREBOARD</a></li>
                </ol>
            </div>
            <h4 class="page-title">Add New Team</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Others/Tournaments/Insert_New_Team') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Team Name</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="team_name" placeholder="Team Name" value="" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Player 1</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="player1" placeholder="Player 1 Name" value="" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Player 2</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="player2" placeholder="Player 2 Name" value="" required />
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-info btn-xs waves-effect waves-light">
                                Add New Team
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>