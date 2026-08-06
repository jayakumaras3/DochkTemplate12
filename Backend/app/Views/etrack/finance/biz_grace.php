<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/Fin_admin'); ?>">
                            Finance Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Biz Approval Grace
            </h4>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-xl-12 col-md-12">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="center">#</th>

                            <th>Employee</th>
                            <th>Date</th>
                            <th>Number</th>
                            <th>HR Comment</th>
                            <th>Comment</th>
                            <th>Approve</th>
                            <th>Comment</th>
                            <th>Reject</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($all_grace as $data) {
                            $j++;
                            echo '<tr><td>';
                            echo $j;
                            echo '</td><td>';
                            echo $data['gname'] . ' ' . $data['glast'];
                            echo '</td><td>';
                            echo $data['date'];
                            echo '</td><td>';
                            echo $data['numgrace'];
                            echo '</td><td>';
                            echo $data['remarks_hr'];

                            echo '</td>';

                        ?>
                            <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/reject_grace'); ?>" method="POST"><?= csrf_field() ?>
                                <td>
                                    <input type="text" name="biz_remarks" value="">
                                </td>
                                <td>
                                    <input type="hidden" name="approve_type" value="1">
                                    <input type="hidden" name="grace_id" value="<?php echo $data['grace_id']; ?>">
                                    <button class="btn btn-success btn-xs">Approve</button>
                                </td>
                            </form>
                            <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/reject_grace'); ?>" method="POST"><?= csrf_field() ?>
                                <td>
                                    <input type="text" name="biz_remarks" value="">
                                </td>
                                <td>
                                    <input type="hidden" name="approve_type" value="2">
                                    <input type="hidden" name="grace_id" value="<?php echo $data['grace_id']; ?>">
                                    <button class="btn btn-danger btn-xs">Reject</button>
                                </td>
                            </form>
                        <?php

                            echo '</tr>';
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col-->
</div>