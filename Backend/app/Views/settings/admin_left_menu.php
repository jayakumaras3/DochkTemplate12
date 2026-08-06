<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($link1); ?>"><?php echo $link1_name; ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo $link3_name; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="inbox-leftbar">
                    <div class="mail-list mt-3">
                        <a <?php if ($link3_name == 'Clients') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url('User_login/client_list'); ?>" class="list-group-item border-0"><i class="mdi mdi-chess-king font-18 align-middle me-2"></i>Clients</a>
                        <a <?php if ($link3_name == 'Partners') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url('User_login/partners/partner_list'); ?>" class="list-group-item border-0"><i class="mdi mdi-anchor font-18 align-middle me-2"></i>Partners</a>
                        <a <?php if ($link3_name == 'Notifications') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url('Support/Support_user/notificatoins'); ?>" class="list-group-item border-0"><i class="mdi mdi-bell-ring-outline font-18 align-middle me-2"></i>Notifications</a>
                        <a <?php if ($link3_name == 'Admin Support') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url('Support/Support/admin_support'); ?>" class="list-group-item border-0"><i class="mdi mdi-phone font-18 align-middle me-2"></i>Support</a>
                        <a <?php if ($link3_name == 'holiday/holidays') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url('holiday/holidays') ?>" class="list-group-item border-0"><i class="mdi mdi-calendar font-18 align-middle me-2"></i>Holidays</a>
                        <a <?php if ($link3_name == 'Access') echo 'class="text-danger fw-bold"'; ?> href="<?php echo base_url('holidays/access') ?>" class="list-group-item border-0"><i class="mdi mdi-lock-open-check-outline font-18 align-middle me-2"></i>Access List</a>
                    </div>
                </div>