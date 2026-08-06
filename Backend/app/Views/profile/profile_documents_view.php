<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('User_login/profile'); ?>">
                            Profile
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">My Documents</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-md-12">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">

                <form action="<?php echo base_url('User_login/profile/uploadDoc') ?>" method="post" enctype="multipart/form-data"><?= csrf_field() ?>

                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-4 col-form-label">Document Type</label>
                        <div class="col-8 col-xl-8">
                            <select class="form-select" name="document_type">
                                <option value="1">Resume</option>
                                <option value="2">PAN Card</option>
                                <option value="3">Permanent Address Proof - Voter ID/ Driving License/Aadhar Card</option>
                                <option value="4">Present Address Proof</option>
                                <option value="5">Experience/Service certificate from ALL the previous employers</option>
                                <option value="6">Payslips (Last three months)</option>
                                <option value="7">10th Certificate and Marksheet</option>
                                <option value="8">12th Certificate and Marksheet</option>
                                <option value="9">Degree Marksheet And Certificates</option>
                                <option value="10">Any other educational certificates</option>
                                <option value="11">Form 16 from the previous organization</option>
                                <option value="12">Caste supporting document</option>
                                <option value="13">Permanent address Proof</option>
                                <option value="14">Aadhar Card</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-4 col-form-label">Password for Document (if Any)</label>
                        <div class="col-8 col-xl-8">
                            <input type="text" name="pazzword" class="form-control" value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <input type="file" name="file" id="file" data-plugins="dropify" data-default-file="" required />
                        </div>
                        <span style="color:red; font-style: italic;">Only .jpg file allowed.</span>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <?php if (isset($filevalidation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $filevalidation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="submit" value="Upload Document" name="submit" class="btn btn-outline-success btn-xs waves-effect waves-light form-control">
                        </div>

                    </div>

                </form>
            </div>
        </div>
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
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $j = 0;
                        foreach ($doc_data as $data) {
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
                                case 0:
                                    echo 'Deleted';
                                    break;
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
                            $id_user  = session()->get('username');
                        ?>
                            <a href="<?php echo base_url('assets/assets/uploads/profile/' . $id_user . '/' . $data['doc_folder'] . '/' . $data['doc_name']); ?>" target="_blank">
                                <i class="mdi mdi-eye-outline"></i>
                            </a>
                            <?php
                            echo '</td><td>';
                            if ($data['status'] <= 1) {
                            ?>
                                <form class="form-horizontal" action="<?php echo base_url('User_login/profile/delete_doc'); ?>" method="POST"><?= csrf_field() ?>
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