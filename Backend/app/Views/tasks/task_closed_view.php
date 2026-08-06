<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('tasks');?>">Tasks</a></li><b> &nbsp;> &nbsp;</b>
         
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="x_panel">
            <table class="table  table-sm table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project Name</th>
                        <th>Course Name</th>
                        <th>Due Date</th>
                        <th width=5%>View</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $j = 0;
                    foreach ($oldtask as $eacholdtask) {
                        $j = $j + 1; ?>
                        <td><?php echo $j ?></td>
                        <td><?php echo $eacholdtask['projectname'] ?></td>
                        <td><?php echo $eacholdtask['course_name'] ?></td>
                        <td><?php echo $eacholdtask['duedate'] ?></td>
                        <td> <?php if ($eacholdtask['coursetype'] == 2) {
                                    echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon  btn-success" onclick="popup(0, ' . $eacholdtask['courseid'] . ',1,1)">';
                                } else if ($eacholdtask['coursetype'] == 5) {
                                    echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon  btn-success" onclick="popup(0, ' . $eacholdtask['courseid'] . ',1,5)">';
                                } else {
                                    echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon  btn-info" onclick="popup(0,' . $eacholdtask['courseid'] . ',1,3)">';
                                }
                                echo '<span class="icon-play"></span>';
                                echo '</a>'; ?>
                        </td>
                    <?php }
                    foreach ($oldpagetask as $eacholdpagetask) {
                        $j = $j + 1; ?>
                        <td><?php echo $j ?></td>
                        <td><?php echo $eacholdpagetask['pagename'] ?></td>
                        <td><?php echo $eacholdpagetask['pagestatusname'] ?></td>
                        <td><?php echo $eacholdpagetask['duedate'] ?></td>
                        <td> <?php echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon  btn-warning" onclick="popup(0,', $eacholdpagetask['courseid'] . ', ' . $pageid . ',2)">';
                                echo '<span class="icon-play"></span>';
                                echo '</a>'; ?>
                        </td>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div><!-- /.row -->
</div><!-- /.row -->