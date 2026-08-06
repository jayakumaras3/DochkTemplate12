<div class="col-lg-12 col-xl-12 mg-t-10">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('marketplace/dashboard'); ?>">
                                Marketplace Dashboard
                            </a>
                        </li>
                    </ol>
                </div>
                <h4 class="page-title"><?php echo $header; ?></h4>
            </div>
        </div>
    </div>
    <?php if (session()->get('success')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->get('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->get('error')) : ?>
        <div class="alert alert-danger" role="alert">
            <?= session()->get('error') ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card card-body">
                <div class="pricing-padding" style="text-align:center">

                    <div>
                        <div><span style="font-size:30px ;font-weight:bold"><?php echo $price ?></span><span style="font-size:small">/.Monthly

                                <div>Billed Monthly</div>
                        </div><br />
                        <div class="pricing-details">
                            <div class="pricing-item">
                                <div><i style="color:green" data-feather="check-circle"></i> 1 user</div>
                                <div class="pricing-item-label"></div>
                            </div><br />
                            <div class="pricing-item">
                                <div class="pricing-item-icon"><i style="color:green" data-feather="check-circle"></i> All Course</div>
                                <div class="pricing-item-label"></div>
                            </div><br />
                            <div class="pricing-item">
                                <div class="pricing-item-icon"><i style="color:green" data-feather="check-circle"></i> Free Updates</div>
                                <div class="pricing-item-label"></div>
                            </div><br />
                            <div class="pricing-item">
                                <div class="pricing-item-icon"><i style="color:green" data-feather="check-circle"></i> Courses added weekly</div>
                                <div class="pricing-item-label"></div>
                            </div>

                        </div>
                    </div><br />
                    <div style="text-align:center">
                        <form action="<?php echo base_url('Stripe/checkout/monthlySubscription') ?>" method="POST"><?= csrf_field() ?>
                            <button type="submit" class="btn btn-primary btn-block col-md-12"><a>Subscribe <i class="fas fa-arrow-right"></i></a></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 col-lg-8">

            <div class="card">
                <div class="card-header">
                    <h4>Billing History</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>TRX-ID</th>
                                    <th>Payment Date</th>
                                    <!-- <th>Expire Date</th> -->
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Details</th>
                                    <!-- <th>Delete</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php $j = 0;
                                if (!empty($myBilling)) {
                                    foreach ($myBilling as $k) {
                                        if ($k['mode'] == 1) {
                                            $mode = 'OL';
                                        } else {
                                            $mode = 'OFL';
                                        }

                                        if ($k['currency'] == 1) {
                                            $currency = 'USD';
                                        } else {
                                            $currency = 'USD';
                                        }
                                ?>
                                        <tr>
                                            <td><?php echo $mode . '-' . $k['bill_id']; ?></td>
                                            <td><?php echo date('m-d-Y', $k['createdon']); ?></td>
                                            <!-- <td><?php echo date('m-d-Y', strtotime($k['expire_date'])); ?></td> -->
                                            <td><?php echo $currency . ' ' . $k['amount']; ?></td>
                                            <td><?php $status = $k['status'];
                                                switch ($status) {
                                                    case 0:
                                                        echo 'Deleted';
                                                        break;
                                                    case 1:
                                                        echo 'Success';
                                                        break;
                                                }

                                                ?></td>
                                            <td>
                                                <?php if ($status != 0) { ?>
                                                    <form class="form-horizontal" action="<?php echo base_url('Billing/viewBillingDetails') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="bill_id" value="<?php echo $k['bill_id'] ?>">
                                                        <button type="submit" class="btn btn-xs widget-icon btn-success"><span class="fas fa-eye"></span></button>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                            <!-- <td>
                                                <?php if ($status == 1) { ?>
                                                    <form class="form-horizontal" action="<?php echo base_url('Billing/delete_billing') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="bill_id" value="<?php echo $k['bill_id'] ?>">
                                                        <button type="submit" class="btn btn-xs widget-icon btn-danger"><span class="fas fa-trash"></span></button>
                                                    </form>
                                                <?php } ?>
                                            </td> -->
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
</div>
</div>
</div>
</div>
<br /><br />
<!-- <div class="col-12 col-md-4 col-lg-4">
                    <div class="card card-body">
                        <form class="form-inline" action=" <?php echo base_url('billing/applyCoupon') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                            <div class="form-group col-md-12">
                                <input type="text" class="form-control col-md-12" name="coupon_code" placeholder="Coupon Code" />
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-info btn-xs col-md-4">
                                    Apply Coupon
                                </button>
                            </div>
                        </form>
                    </div>
                </div> -->
</div>