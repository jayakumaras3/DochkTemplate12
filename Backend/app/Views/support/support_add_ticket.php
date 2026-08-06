<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Support/Support_user'); ?>"><?php echo $header2 ?></a></li>

                </ol>
            </div>
            <h4 class="page-title"><?php echo $header ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('Support/Support_user/createNewTicket') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <div class="mb-3">
                        <textarea  name="description" class="form-control" value="" required="" ></textarea>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-outline-primary btn-xs square-pill waves-effect waves-light">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>