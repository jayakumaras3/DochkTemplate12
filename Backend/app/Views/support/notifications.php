<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Notifications</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <?php
                $userlevel = session()->get('userlevel');
                $arrayuserlevel = explode(',', $userlevel);
                if (in_array('6', $arrayuserlevel)) { ?>
                    <form action="<?php echo base_url("Support/Support_user/add_notificatoins") ?>" method="POST"><?= csrf_field() ?>
                        <button type="submit"
                            class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light  float-end"><i
                                class="mdi mdi-plus"></i> Create New Notifications</button>
                    </form>
                <?php } ?>

                <p class="text-muted font-13 mb-4"></p>
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th width="10%">ID</th>
                            <th>Small Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th width="10%">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        if (!empty($latest_notifications)) {

                            foreach ($latest_notifications as $k) {
                                ?>
                                <tr>
                                    <td><?php echo $k['notification_id']; ?></td>
                                    <td><?php echo $k['short_name']; ?></td>
                                    <td><?php echo $k['start_date']; ?></td>
                                    <td><?php echo $k['end_date']; ?></td>
                                    <td>
                                        <form class="form-horizontal"
                                            action="<?php echo base_url('Support/Support_user/delete_notificatoins') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="notification_id"
                                                value="<?php echo $k['notification_id'] ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span
                                                    class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>

                                </tr>
                            <?php }
                        } ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>
</div>