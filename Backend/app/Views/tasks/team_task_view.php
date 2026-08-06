<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Team Tasks</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table dt-responsive w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Designation</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 0;
                        if ($team_task) {
                            foreach ($team_task as $index => $view_team_data) {
                                $sno++;
                        ?>
                                <tr>
                                    <td><?php echo $sno; ?></td>
                                    <td><?php echo $view_team_data['name'] . ' ' . $view_team_data['last_name']; ?></td>
                                    <td><?php echo $view_team_data['designation']; ?></td>
                                    <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/team_active_tasks_new') ?>" method="POST"><?= csrf_field() ?>
                                        <td>
                                            <input id="start_date_<?php echo $index; ?>" name="start_date" class="date-picker form-control"
                                                placeholder="yyyy-mm-dd" type="text"
                                                onfocus="this.type='date'" onclick="this.type='date'"
                                                onblur="this.type='text'" onmouseout="timeFunctionLong(this)"
                                                value="" onchange="updateEndDateMin(<?php echo $index; ?>)">
                                        </td>

                                        <td>
                                            <input id="end_date_<?php echo $index; ?>" name="end_date" class="date-picker form-control"
                                                placeholder="yyyy-mm-dd" type="text"
                                                onfocus="this.type='date'" onclick="this.type='date'"
                                                onblur="this.type='text'" onmouseout="timeFunctionLong(this)"
                                                value="">
                                        </td>
                                        <td>
                                            <input type="hidden" name="temp_user" value="<?php echo $view_team_data['id_user']; ?>">
                                            <button type="submit" style="all: unset; cursor:pointer;"> <i class="mdi mdi-clipboard-outline font-16"></i></button>
                                    </form>
                                    </td>

                                </tr>

                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<script>
    function timeFunctionLong(input) {
        setTimeout(function() {
            input.type = 'text';
        }, 60000);
    }

    function updateEndDateMin(index) {
        var startDate = document.getElementById('start_date_' + index).value;
        var endDateInput = document.getElementById('end_date_' + index);
        endDateInput.min = startDate;
    }
</script>