<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/dashboard/policies'); ?>">
                            Touchstone Policies
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                <?php echo $policy_name[0]['document_name']; ?>
            </h4>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Pages</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentpageid = 0;
                        $j = 0;
                        foreach ($policy_list as $list) {
                            $j++;
                            echo '<tr>';
                            echo '<td>';
                        ?>
                            <form class="form-horizontal" action="<?php echo base_url('etrack/dashboard/view_policy_by_id'); ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="emd_id" value="<?php echo $emd_id; ?>">
                                <input type="hidden" name="empg_id" value="<?php echo $list['empg_id']; ?>">
                                <?php if ($empg_id == $list['empg_id']) {
                                    $currentpageid = $j;
                                ?>

                                    <button class="btn btn-sm widget-icon btn-success active"><?php echo $list['page_name']; ?></button>
                                <?php } else { ?>
                                    <button class="btn btn-sm widget-icon btn-info"><?php echo $list['page_name']; ?></button>
                                <?php } ?>
                            </form>
                        <?php
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (count($policy_accepted) > 0) { ?>
            <div class="card">

                <?php if ($policy_accepted[0]['accepted_on'] < $policy_name[0]['last_updated_on']) { ?>
                    <div class="card-body">
                        <p>This policy has been updated on <?php echo date('d M Y', $policy_name[0]['last_updated_on']); ?>. Please accept the updated policy.</p>
                        <form class="form-horizontal" action="<?php echo base_url('etrack/dashboard/accept_policy'); ?>" method="POST"><?= csrf_field() ?>
                            <input type="hidden" name="emd_id" value="<?php echo $emd_id; ?>">
                            <button class="btn btn-outline-primary waves-effect btn-xs waves-light">Accept Updated Policy</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="card-body">
                        <p>You have accepted this policy on <?php echo date('d M Y', $policy_accepted[0]['accepted_on']); ?>.</p>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/dashboard/accept_policy'); ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="emd_id" value="<?php echo $emd_id; ?>">
                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light">Accept Policy</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h2><u><?php echo $policy_list[$currentpageid - 1]['page_name']; ?></u></h2>
            </div>
            <div class="card-body">
                <?php
                if (count($policy_pages) > 0) {
                    $j = 0;
                    foreach ($policy_pages as $list) {
                        $type = $list['type'];
                        if ($type == 88) {
                            echo '<h3>';
                        }
                        if ($type == 89) {
                            echo '<h4>';
                        }
                        if ($type == 101) {
                            echo '<p>';
                        }
                        echo $list['content1'];
                        if ($type == 101) {
                            echo '</p>';
                        }
                        if ($type == 89) {
                            echo '</h4>';
                        }
                        if ($type == 88) {
                            echo '</h3>';
                        }
                    }
                }
                ?>

            </div>
        </div>
    </div>
</div>