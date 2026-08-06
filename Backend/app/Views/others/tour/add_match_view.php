<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments') ?>">Active Tournaments</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments/Scoreboard') ?>">SCOREBOARD</a></li>
                </ol>
            </div>
            <h4 class="page-title">Add New Match</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Others/Tournaments/Insert_New_Match') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Round Name</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="round" placeholder="" value="" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Team 1</label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="team_1" required>
                               
                                <?php foreach ($teams as $team) : ?>
                                    <option value="<?php echo $team['tt_id']; ?>"><?php echo $team['team_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Team 2</label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="team_2" required>
                              
                                <?php foreach ($teams as $team) : ?>
                                    <option value="<?php echo $team['tt_id']; ?>"><?php echo $team['team_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Match date</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="match_date" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
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
                            <input class="form-control" id="example-time" name="slot" type="time">
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-info btn-xs waves-effect waves-light">
                                Add New Match
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>