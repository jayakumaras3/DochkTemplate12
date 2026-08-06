<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/personal'); ?>">
                            Admin HR Personal Data
                        </a>
                    </li>
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/view_personal_data'); ?>">
                            User Personal Data
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                User Personal Documents - (<?php echo $username[0]['name'].' '.$username[0]['last_name']; ?>)
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
                            <th>Document Type</th>
                            <th>Password</th>
                            <th>Status</th>
                            <th>Details</th>
                            <th>Approve</th>
                            <th>Reject</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php

                        $j = 0;
                        foreach ($user_docs as $data) {
                            $j++;
                            echo '<tr><td>';
                            echo $j;
                            echo '</td><td>';
                            switch ($data['doc_type']) {
                                case 1:
                                    echo 'Resume';
                                    break;
                                case 2:
                                    echo 'PAN Card';
                                    break;
                                case 3:
                                    echo 'Permanent Address Proof - Voter ID/ Driving License/Aadhar Card';
                                    break;
                                case 4:
                                    echo 'Present Address Proof';
                                    break;
                                case 5:
                                    echo 'Experience/Service certificate from ALL the previous employers';
                                    break;
                                case 6:
                                    echo 'Payslips (Last three months)';
                                    break;
                                case 7:
                                    echo '10th Certificate and Marksheet';
                                    break;
                                case 8:
                                    echo '12th Certificate and Marksheet';
                                    break;
                                case 9:
                                    echo 'Degree Marksheet And Certificates';
                                    break;
                                case 10:
                                    echo 'Any other educational certificates';
                                    break;
                                case 11:
                                    echo 'Form 16 from the previous organization';
                                    break;
                                case 12:
                                    echo 'Caste supporting document';
                                    break;
                                case 13:
                                    echo 'Permanent address Proof';
                                    break;
                                case 14:
                                    echo 'Aadhar Card';
                                    break;
                            }
                            echo '</td><td>';
                            echo $data['passwd'];
                            echo '</td><td>';
                            switch ($data['status']) {
                                case 1:
                                    echo 'Awaiting Approval';
                                    break;
                                case 2:
                                    echo 'Approved';
                                    break;
                                case 3:
                                    echo 'Rejected';
                                    break;
                            }
                            echo '</td><td>';
                            //echo $username;
                            $id_user  = $username[0]['username'];
                        ?>
                            <a href="<?php echo base_url('assets/assets/uploads/profile/' . $id_user . '/' . $data['doc_folder'] . '/' . $data['doc_name']); ?>" target="_blank">
                                <i class="mdi mdi-eye-outline"></i>
                            </a>
                            <?php
                            echo '</td><td>';
                            if ($data['status'] <= 1) {
                            ?>
                                <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/approve_doc'); ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="status" value="2">
                                    <input type="hidden" name="returnid" value="1">
                                    <input type="hidden" name="et_doc_id" value="<?php echo $data['et_doc_id']; ?>">
                                    <button class="btn btn-success btn-xs">Approve</button>
                                </form>
                            <?php
                            }
                            echo '</td><td>';
                            if ($data['status'] <= 1) {
                            ?>
                                <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/approve_doc'); ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="status" value="3">
                                    <input type="hidden" name="returnid" value="1">
                                    <input type="hidden" name="et_doc_id" value="<?php echo $data['et_doc_id']; ?>">
                                    <button class="btn btn-danger btn-xs">Reject</button>
                                </form>
                            <?php
                            }
                            echo '</td><td>';
                            if ($data['status'] <= 1) {
                            ?>
                                <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/approve_doc'); ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="status" value="0">
                                    <input type="hidden" name="returnid" value="1">
                                    <input type="hidden" name="et_doc_id" value="<?php echo $data['et_doc_id']; ?>">
                                    <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                </form>
                        <?php
                            }
                            echo '</td></tr>';
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col-->
</div>