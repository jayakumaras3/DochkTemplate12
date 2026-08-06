<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_list/my_client_list'); ?>">Clients</a></li>
               
                </ol>
            </div>
            <h4 class="page-title">Create New Client</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('User_login/client_list/add_client') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">Client <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="client_name" placeholder="Client Name" required />
                            </div>
                        </div>
                       <!--  <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">Client <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="client_fullname" placeholder="Client Full Name" required />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Description</label>
                                <div>
                                    <input class="form-control" name="description" type="hidden" />
                                    <textarea class="ckeditor" name="requirement" required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Location</label>
                                <div>
                                    <input class="form-control" name="location" type="text" />
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Address</label>
                                <div>
                                    <input class="form-control" name="address" type="text" />
                                    <input class="form-control" name="redirect_url" type="text" value="<?= $redirect_url ?>" />
                            </div>
                        </div> -->
                 
              
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    <?php echo  lang('Buttons.Create') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>