<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin'); ?>">
                            Admin Attendance
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
               User Access Card Data
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Day</th>
                            <th>In</th>
                            <th>Out</th>
                            <th>Total</th>
                            <th>Break</th>
                            <th>Actual</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($access_card as $data) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $data['start_date']; ?></td>
                                <td><?php echo $data['timein']; ?></td>
                                <td><?php echo $data['timeout']; ?></td>
                                <td><?php echo $data['totalhrs']; ?></td>
                                <td><?php echo $data['breakhr']; ?></td>
                                <td><?php echo $data['actualhr']; ?></td>
                                <?php $remarks = $data['remarks'];
                                    echo '<td>' . $remarks . '</td>';
                                ?>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>