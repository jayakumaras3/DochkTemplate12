 <?php $client = session()->get('client');
    $clientarray = explode(',', $client) ?>
 <div class="row">
     <div class="col-12">
         <div class="page-title-box">
             <div class="page-title-right">
                 <ol class="breadcrumb m-0">
                     <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/Certification_Portal'); ?>">Certifications</a></li>

                 </ol>
             </div>
             <h4 class="page-title">Payment History</h4>
         </div>
     </div> 
 </div>
 <div class="row">
     <div class="col-lg-12">
         <div class="card">
             <div class="card-body">

                 <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                     <thead>
                         <tr>
                             <th><?php echo lang('UI_Text.Certification') ?></th>
                             <th><?php echo lang('UI_Text.Amount') ?></th>
                             <th><?php echo lang('UI_Text.Transaction_Id') ?></th>
                             <th><?php echo lang('UI_Text.Paid_On') ?></th>
                             <th><?php echo lang('UI_Text.Status') ?></th>
                             <!-- <th></th> -->
                         </tr>
                     </thead>
                     <?php foreach ($paymentHistory as $payment) : ?>
                         <tr>
                             <td><?= esc($payment['cert_name']) ?></td>
                             <td><?= '₹' . number_format($payment['final_amount'], 2) ?></td>
                             <td><?= esc($payment['transaction_id']) ?></td>
                             <td><?= date('d M Y', ($payment['createdon'])) ?></td>
                             <td>
                                 <?php
                                    $status = strtoupper($payment['payment_status']);

                                    switch ($status) {
                                        case 'PAID':
                                            $badgeClass = 'bg-success';
                                            break;

                                        case 'PENDING':
                                            $badgeClass = 'bg-warning text-dark';
                                            break;

                                        case 'FAILED':
                                            $badgeClass = 'bg-danger';
                                            break;

                                        case 'REFUNDED':
                                            $badgeClass = 'bg-info';
                                            break;

                                        case 'CANCELLED':
                                            $badgeClass = 'bg-secondary';
                                            break;

                                        default:
                                            $badgeClass = 'bg-dark';
                                            break;
                                    }
                                    ?>


                                 <span class="badge <?= $badgeClass ?>">
                                     <?= esc($payment['payment_status']) ?>
                                 </span>
                             </td>
                             <!-- <td>
                                 <a href="<?= base_url('Certification/invoice/' . $payment['id']) ?>"
                                     class="btn btn-sm btn-outline-primary">
                                     Download
                                 </a>
                             </td> -->
                         </tr>
                     <?php endforeach; ?>
                 </table>
             </div>
         </div>
     </div>
 </div>