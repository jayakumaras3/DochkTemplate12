<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Tournaments') ?>">Active Tournaments</a></li>
                    
                </ol>
            </div>
            <h4 class="page-title">Edit Tournament Details</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Others/Tournaments/Update_Tournament') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Tournament Name</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="tournament_name" placeholder="Tournament Name" value="<?php echo $tournament_details['tournament_name'] ?>" required />

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Start date</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="start_date" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $tournament_details['start_date'] ?>">
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
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">End date</label>
                        <div class="col-8 col-xl-9">
                            <input id="end_date" name="end_date" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $tournament_details['end_date'] ?>">
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
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Status</label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select" name="status" required>
                                <option value="1" <?php if ($tournament_details['status'] == 1) echo 'selected'; ?>>Active</option>
                                <option value="2" <?php if ($tournament_details['status'] == 2) echo 'selected'; ?>>Editing</option>
                                <option value="0" <?php if ($tournament_details['status'] == 0) echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <input type="hidden" name="tour_id" value="<?php echo $tournament_details['tour_id'] ?>">
                        <div class="col-8 col-xl-9">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-info btn-xs waves-effect waves-light">
                                Update Tournament
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>