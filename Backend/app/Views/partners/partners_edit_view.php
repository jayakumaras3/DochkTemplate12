<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header; ?></a></li>
             
                </ol>
            </div>
            <h4 class="page-title"><?php echo  $sub_header_1 ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="inbox-leftbar">
                    <div class="mail-list mt-3">
                        <a href="<?php echo base_url('User_login/partners/partner_list'); ?>" class="list-group-item border-0"><i class="fe-anchor font-18 align-middle me-2"></i>Partners</a>
                        <a href="<?php echo base_url('Support/Support/admin_support'); ?>" class="list-group-item border-0"><i class="fe-smartphone font-18 align-middle me-2"></i>Support</a>
                    </div>
                </div>
                <div class="inbox-rightbar">
                    <div class="section-body">
                        <?php $client = session()->get('client');
                        // if ($client == 0) { 
                        ?>
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Upload Logo</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!$row['logo']  == '') {
                                        ?>
                                            <div class="head bg-dot30 np tac">
                                                <img style="max-height:100px;" src="<?php echo base_url() . '/public/aristo_public/images/partners/' . $row['pr_id'] . '/' . $row['logo'] ?>" class="img-squre img-thumbnail" />
                                            </div><br />
                                        <?php }
                                        ?>
                                        <form class="form-horizontal1" enctype="multipart/form-data" action="<?php echo base_url('User_login/partners/uploadPartnerlogo') ?>" method="POST"><?= csrf_field() ?>
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <input type="file" name="file" required />
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <input type="hidden" name="pr_id" value="<?php echo $row['pr_id']; ?>">
                                                    <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light">Upload</button>
                                                </div>
                                            </div>
                                            <?php if (isset($logovalidation)) : ?>
                                                <div class="form-group col-md-3">
                                                    <div class="alert alert-white" role="alert">
                                                        <?= $logovalidation->listErrors() ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php //} 
                        ?>
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 mg-t-10">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="form-inline" action="<?php echo base_url($form_link) ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                                            <div class="row">
                                                <div class="form-group col-md-4 mb-3">
                                                    <label>Partner </label>
                                                    <input type="text" class="form-control col-md-12" name="partner_name" placeholder="Partner Name" value="<?php echo $row['partner_name'] ?>" />
                                                </div>
                                                <div class="form-group col-md-4  mb-3">
                                                    <label>Location</label>
                                                    <input type="text" class="form-control col-md-12" name="location" placeholder="Location" value="<?php echo $row['location'] ?>" />
                                                </div>
                                                <div class="form-group col-md-4 mb-3">
                                                    <label>Email ID</label>
                                                    <input type="text" class="form-control col-md-12" name="email_id" placeholder="Email ID " value="<?php echo $row['email_id'] ?>" />
                                                </div>
                                                <div class="form-group col-md-4 mb-3">
                                                    <label>Company Name</label>
                                                    <input type="text" class="form-control col-md-12" name="company" placeholder="Company Name" value="<?php echo $row['company'] ?>" />
                                                </div>
                                                <div class="form-group col-md-4 mb-3">
                                                    <label>Phone number</label>
                                                    <input type="text" class="form-control" name="contact" placeholder="Phone number" value="<?php echo $row['contact'] ?>" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group  col-md-12">
                                                    <?php if (isset($coursevalidation)) : ?>
                                                        <div class=col-12 col-sm-4>
                                                            <div class="alert alert-white" role="alert">
                                                                <?= $coursevalidation->listErrors() ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <input type="hidden" name="pr_id" value="<?php echo $row['pr_id'] ?>" />
                                                    <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light col-md-4">
                                                        Update
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>