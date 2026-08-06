<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_list') ?>">Clients</a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">Info</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Course Count</th>
                            <th>Marketplace Count</th>
                            <th>Learning Plan Count</th>


                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <form class="form-horizontal"
                                    action="<?php echo base_url('SCORM/scorm_client/view_client_course_assigned') ?>"
                                    method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="client_id" value="<?php echo $cid; ?>">
                                    <button type="submit"
                                        class="btn btn-outline-dark btn-xs waves-effect waves-light"><?php echo $getCourseCount['course_count'] ?></button>
                                </form>
                            </td>
                            <td>
                                <form class="form-horizontal" action="<?php echo base_url('marketplace/admin') ?>"
                                    method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="type" value="1">

                                    <button type="submit"
                                        class="btn btn-outline-warning btn-xs waves-effect waves-light"><?php echo $getmarketCount['ml_count'] ?></button>
                                </form>
                            </td>
                            <td>
                                <form class="form-horizontal" action="<?php echo base_url('marketplace/admin') ?>"
                                    method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="type" value="2">

                                    <button type="submit"
                                        class="btn btn-outline-info btn-xs waves-effect waves-light"><?php echo $getlearningCount['ml_count'] ?></button>
                                </form>
                            </td>


                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<?php

use Faker\Provider\Payment;

$userlevel = session()->get('userlevel');
$arrayuserlevel  = explode(',', $userlevel);
$sessionclient = session()->get('client');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Purchase History</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>User</th>
                                    <th>Course Name</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Paid On</th>
                                    <th>Invoice PDF</th>
                                    <th>Invoice</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($purchasedCourses)): ?>
                                    <?php foreach ($purchasedCourses as $course):
                                        if ($course['type'] == 1) {
                                            $type = 'OL';
                                        } else {
                                            $type = 'OFL';
                                        } ?>
                                        <tr>
                                            <td><?= $type . '-' . esc($course['bill_id']); ?></td>
                                            <th> <?php echo $course['first_name'] ?></th>
                                            <td><?= esc($course['course_name']); ?></td>
                                            <td>
                                                <?php if ($course['status'] == 1): ?>
                                                    <span class="badge bg-success">Successful</span>
                                                <?php elseif ($course['status'] == 2): ?>
                                                    <span class="badge bg-danger">Refund Pending</span>
                                                <?php elseif ($course['status'] == 3): ?>
                                                    <span class="badge bg-info">Refunded</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php endif; ?>

                                            </td>
                                            <td><?= esc($course['amount']); ?> <?= strtoupper(esc($course['currency'])); ?></td>
                                            <td>
                                                <?php if (!empty($course['createdon']) && $course['status'] == 1): ?>
                                                    <?= date('d-m-Y H:i', $course['createdon']); ?>
                                                <?php elseif (!empty($course['refunded_on']) && $course['status'] == 3): ?>
                                                    <?= date('d-m-Y H:i', $course['refunded_on']); ?>
                                                <?php elseif (!empty($course['refunded_on']) && $course['status'] == 2): ?>
                                                    <?= date('d-m-Y H:i', $course['refunded_on']); ?>
                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($course['payment_intent_id'])): ?>
                                                    <a href="<?= base_url('Payment/Billing/generateInvoicepdf/' . esc($course['payment_intent_id'])); ?>" target="_blank" class="btn btn-outline-info btn-xs square-pill waves-effect waves-light" title="View Invoice">
                                                        <i class="fe-download"></i>
                                                    </a>
                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($course['payment_intent_id'])): ?>
                                                    <a href="https://dashboard.stripe.com/payments/<?= esc($course['payment_intent_id']); ?>" target="_blank" class="btn btn-outline-primary btn-xs square-pill waves-effect waves-light" title="View Invoice">
                                                        <i class="fe-clock"></i>
                                                    </a>
                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                // Allow cancellation within 7 days
                                                $canCancel = ($course['status'] == 1) && ((time() - $course['createdon']) <= 7 * 24 * 60 * 60);
                                                if ($canCancel): ?>
                                                    <a href="<?= base_url('Stripe/checkout/cancellation/' . $course['payment_intent_id']); ?>" class="btn btn-outline-danger btn-xs square-pill waves-effect waves-light" onclick="return confirm('Are you sure you want to cancel this course? This will trigger a refund.');">Cancel</a>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No purchased courses found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>