
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
                Create New Game
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Game/Gamification/addGame'); ?>" method="POST"
                    id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label for="year">Game Name</label>
                            <input type="text" class="form-control" name="game_name" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="month">Select Course</label>
                            <select class="form-select" name="course_id" required>
                                <option value="">-- Select Course --</option>
                                <?php
                                foreach ($clientCourseddata as $courses) {
                                    ?>
                                    <option value="<?php echo $courses['scourse_id']; ?>">
                                        <?php echo $courses['course_name']; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="year">Start Date</label>
                            <input id="birthday" name="start_on" class="date-picker form-control"
                                placeholder="Start Date" type="text" required="required" type="text"
                                onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'"
                                onblur="this.type='text'" onmouseout="timeFunctionLong(this)"
                                value="<?= set_value('start_date') ?>">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function () {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="year">End Date</label>
                            <input id="birthday" name="end_on" class="date-picker form-control" placeholder="End Date"
                                type="text" required="required" type="text" onfocus="this.type='date'"
                                onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'"
                                onmouseout="timeFunctionLong(this)" value="<?= set_value('start_date') ?>">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function () {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="year">Attempts Allowed</label>
                            <input type="number" class="form-control" name="attempts" min="0">
                        </div>
                        <div class="col-md-12 mt-2">
                            <button type="submit" class=" btn btn-outline-info btn-xs waves-effect waves-light"
                                id="submitButton">Create Game</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>