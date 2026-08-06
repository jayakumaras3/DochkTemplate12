<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel  = explode(',', $userlevel);
$sessionclient = session()->get('client');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Partners</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <form class="form-horizontal" action="<?php echo base_url('User_login/Partners') ?>" method="POST"><?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light  float-end"> + <?php echo $add ?></button>
                </form>
                <h4 class="header-title mb-4"></h4>
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Partner Name</th>
                            <?php if (session()->get('client') == 1) { ?>
                                <th>Client / Users</th>
                            <?php } ?>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Del</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $new = array();
                        foreach ($partnerlist as $eachpartnerlist) { ?>

                            <tr>
                                <td><?php echo $eachpartnerlist['partner_name'] ?></td>

                                <?php if (session()->get('client') == 1) { ?>

                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('User_login/client_list') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="pr_id" value="<?php echo $eachpartnerlist['pr_id'] ?>">
                                            <button type="submit" class="btn btn-outline-dark btn-xs waves-effect waves-light"><?php $db = \Config\Database::connect();
                                                                                                                                $qc = $db->query("SELECT count(id_c) as partners_client_count FROM client  where partner_code = " . $eachpartnerlist['pr_id'] . " and status = 1");
                                                                                                                                $resultc = $qc->getResultArray();
                                                                                                                                $db = \Config\Database::connect();
                                                                                                                                $qc = $db->query("SELECT count(u.id_user) as user_count  FROM client as c  
                                                                                                                                    left join dropdown_users as du on du.fk_id_d = c.id_c and du.fk_id_dc = 1 and du.status =1
                                                                                                                                    left join users as u on u.id_user = du.fk_id_user and u.valid =1 
                                                                                                                                    where c.partner_code = " . $eachpartnerlist['pr_id'] . " and  c.status = 1");
                                                                                                                                $resultq = $qc->getResultArray();
                                                                                                                                echo $resultc['0']['partners_client_count'] . ' / ';
                                                                                                                                echo $resultq['0']['user_count'];
                                                                                                                                ?></button>
                                        </form>
                                    </td>
                                <?php
                                } ?>
                                <td><?php echo date('m-d-Y', $eachpartnerlist['createdon']) ?></td>
                                <td><?php echo ($eachpartnerlist['status'] == 0) ? 'Inactive' : 'Active' ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($edit_link) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="pr_id" value="<?php echo $eachpartnerlist['pr_id'] ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                    </form>
                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($delete_link) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="pr_id" value="<?php echo $eachpartnerlist['pr_id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        } ?>


                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</div>