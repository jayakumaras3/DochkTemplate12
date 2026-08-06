<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('Game/gamification'); ?>">
                            Gamification Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Edit Game
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Game/Gamification/editGame'); ?>" method="POST" id="submitForm">
                    <div class="row">
                        <div class="row-md-3 mb-2">
                            <label for="year">Game Name</label>
                            <input type="text" class="form-control" name="game_name" value="<?php echo $active_games[0]['game_name']; ?>" required>
                        </div>
                        <div class="row-md-3 mb-2">
                            <label for="month">Select Course</label>
                            <select class="form-control" name="course_id" required>
                                <option value="">-- Select Cours --</option>
                                <?php
                                foreach ($clientCourseddata as $courses) {
                                ?>
                                    <option value="<?php echo $courses['scourse_id']; ?>"

                                        <?php if ($active_games[0]['course_id'] == $courses['scourse_id']) {
                                            echo "Selected";
                                        } ?>><?php echo $courses['course_name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row-md-2 mb-2">
                            <label for="year">Start Date</label>
                            <input id="birthday" name="start_on" class="date-picker form-control" placeholder="Start Date" type="text" required="required" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $active_games[0]['start_on']; ?>">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                        <div class="row-md-2 mb-2">
                            <label for="year">End Date</label>
                            <input id="birthday" name="end_on" class="date-picker form-control" placeholder="End Date" type="text" required="required" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $active_games[0]['end_on']; ?>">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                        <div class="row-md-2 mb-2">
                            <label for="year">Attempts Allowed</label>
                            <input type="number" class="form-control" name="attempts" min="0" value="<?php echo $active_games[0]['attempts']; ?>">
                        </div>
                        <div class="row-md-2 mb-2">
                            <label for="year">Status</label>
                            <select class="form-control" name="status">
                                <option value="1" <?php if ($active_games[0]['status'] == 1) {
                                                        echo "Selected";
                                                    } ?>>Editing</option>
                                <option value="5" <?php if ($active_games[0]['status'] == 5) {
                                                        echo "Selected";
                                                    } ?>>Live</option>
                                <option value="2" <?php if ($active_games[0]['status'] == 3) {
                                                        echo "Selected";
                                                    } ?>>Ended</option>
                                <option value="0" <?php if ($active_games[0]['status'] == 0) {
                                                        echo "Selected";
                                                    } ?>>Delete</option>
                            </select>
                        </div>
                        <div class="row-md-12 mt-2">
                            <input type="hidden" class="form-control" name="game_id" value="<?php echo isset($active_games[0]['game_id'])?$active_games[0]['game_id']:''; ?>">
                            <button type="submit" class=" btn btn-outline-warning btn-xs waves-effect waves-light" id="submitButton">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>