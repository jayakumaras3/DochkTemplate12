<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Completed Tasks</h4>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table id="alternative-page-datatable" class="table dt-responsive w-100">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Assigned By</th>
                                <th>Due Date</th>
                                <th>Started</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($completed_task) {
                                foreach ($completed_task as $completed_task_list) {
                            ?>
                                    <tr>
                                        <td><?php echo $completed_task_list['course_name'] ?></td>
                                        <td><?php echo $completed_task_list['description'] ?></td>
                                        <td><?php echo $completed_task_list['priority'] ?></td>
                                        <td><?php echo $completed_task_list['taskcreatedby'] ?></td>

                                        <td><?php echo $completed_task_list['due_date'] ?></td>
                                        <td><?php echo date("m-d", $completed_task_list['started_on']) ?></td>
                                        <td><?php echo date("m-d", $completed_task_list['completed_on']) ?></td>
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
</div>
</div>
</div>
</div>
</div>
</div>
