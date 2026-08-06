<div class="row">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <?php if ($return_page == 1) { ?>
                                <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/employee_details/appraisals'); ?>">
                                        Appraisals
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($return_page == 2) { ?>
                                <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/view_appraisal_data'); ?>">
                                        Appraisal Data
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($return_page == 3) { ?>
                                <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/view_all_appraisals'); ?>">
                                        Appraisal Data By Date
                                    </a>
                                </li>
                            <?php } ?>
                        </ol>
                    </div>
                    <h4 class="page-title">Appraisal Letter</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <!-- Logo & title -->
                <div class="clearfix mt-4 mb-4">
                    <div class="float-start">
                        <div class="logo logo-dark">
                            <span class="logo-lg">
                                <?php if ($appraisal_letter[0]['template'] == 1) { ?>
                                    <img src="<?php echo base_url('assets/assets/uploads/client_logo/talentquest_logo.png'); ?>" alt="" height="30px">
                                <?php } ?>
                                <?php if ($appraisal_letter[0]['template'] == 2) { ?>
                                    <img src="<?php echo base_url('assets/assets/uploads/client_logo/touchstone_logo.png'); ?>" alt="" height="30px">
                                <?php } ?>
                            </span>
                        </div>
                    </div>

                    <div class="float-end">
                        <h5 class="m-0">Dated - <?php echo date("d/m/Y", $appraisal_letter[0]['last_updated_on']); ?></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12  mt-2">
                        <div class="text-center">
                            <h3><strong><u>Private and Confidential</u></strong></h3><br><br>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <p><strong>Employee Name : </strong> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $appraisal_letter[0]['fname'] . ' ' . $appraisal_letter[0]['last_name']; ?></br>
                                    <strong>Employee ID : </strong> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $appraisal_letter[0]['emp_id']; ?>
                                </p>
                            </div><!-- end col -->
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-sm-12  mt-2">
                        <div class="card">
                            <div class="card-body">
                                Dear <?php echo $appraisal_letter[0]['fname'] . ' ' . $appraisal_letter[0]['last_name']; ?>,<br><br>

                                <p>We congratulate you for your smart work, enthusiasm, dedication and continuous effort in meeting the organization objective.</p>

                                <p>In recognition of this contribution, we are very pleased to inform you that you are being promoted to the post of <strong><?php echo $appraisal_letter[0]['designation']; ?></strong> with effect from <strong><?php echo date('F d, Y', strtotime($appraisal_letter[0]['effectivedate'])); ?></strong>. </p>

                                <p>We hope that you will take up this new responsibility with great enthusiasm and will keep contributing effectively and efficiently towards the objectives & growth of the organization.</p>

                                <p>All the other terms and conditions remain unchanged. Please acknowledge your acceptance of the revised terms by accepting this letter. </p>

                                <p>We look forward to your continued commitment and support during this year. All the best for a successful and rewarding career at Touchstone. </p>

                                <p>Regards,<br><br>
                                    <?php if ($appraisal_letter[0]['template'] == 1) { ?>
                                        <strong>For TalentQuest Solutions Pvt. Ltd.</strong>
                                    <?php } ?>
                                    <?php if ($appraisal_letter[0]['template'] == 2) { ?>
                                        <strong>For TSLAC Solutions Pvt. Ltd.</strong>
                                    <?php } ?>

                                    <br>
                                    <br>
                                <p>HR</p>
                                <?php if ($appraisal_letter[0]['iagree'] == 0) { ?>
                                    <?php if ($return_page == 1) { ?>
                                        <div class="row">
                                            <div class="col-12">

                                            </div>
                                            <div class="col-6">
                                                <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/approve_appraisal'); ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="acceptval" value="1">
                                                    <input type="hidden" name="salid" value="<?php echo $appraisal_letter[0]['salid']; ?>">
                                                    <button class="btn btn-outline-info btn-xs waves-effect waves-light">ACCEPT</button>
                                                </form>
                                            </div>
                                            <div class="col-6">
                                                <p></p>
                                                <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/approve_appraisal'); ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="acceptval" value="2">
                                                    <input type="hidden" name="salid" value="<?php echo $appraisal_letter[0]['salid']; ?>">
                                                    <button class="btn btn-outline-danger btn-xs waves-effect waves-light">Concern : Request Discussion</button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <?php
                                    if ($appraisal_letter[0]['iagree'] == 1) {
                                        echo 'This document was accepted by ' . $appraisal_letter[0]['accname'] . ' ' . $appraisal_letter[0]['acclast'] . ' on ';
                                    }
                                    if ($appraisal_letter[0]['iagree'] == 2) {
                                        echo 'This document was rejected by ' . $appraisal_letter[0]['accname'] . ' ' . $appraisal_letter[0]['acclast'] . ' on ';
                                    }
                                    echo date('Y-m-d', $appraisal_letter[0]['adgreed_on']);  ?>.
                                <?php } ?>

                            </div>
                        </div>
                    </div><!-- end col -->
                </div>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->