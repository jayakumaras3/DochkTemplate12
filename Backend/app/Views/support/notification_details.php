<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Support/Support_user/view_notificatoins'); ?>"><?php echo lang('UI_Text.Notifications'); ?></a></li>
         
                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('UI_Text.Notification_Details'); ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="ps-xl-3 mt-3 mt-xl-0">
                    <h4 class="mb-3"><?php echo $notification_details[0]['short_name']; ?></h4>
                    <p class="mb-0 text-muted"><?php echo $notification_details[0]['name']; ?>
                    <h6 class="text-success text-uppercase"><?php echo $notification_details[0]['start_date']; ?></h6></p>
                    <p class="text-muted mb-4"><?php echo $notification_details[0]['detail_description']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>