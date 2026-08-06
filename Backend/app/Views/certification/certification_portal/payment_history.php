 <?php $client = session()->get('client');
    $clientarray = explode(',', $client) ?>
 <style>
     /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
     .payment-history-card {
         border: none;
         border-radius: 18px;
         overflow: hidden;
         box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
     }

     .payment-history-card table.dataTable thead th {
         border-bottom: 2px solid #eef2f7;
         color: #6c757d;
         font-weight: 700;
         white-space: nowrap;
     }

     [data-bs-theme="dark"] .payment-history-card table.dataTable thead th {
         border-bottom-color: #424e5a;
         color: #cedeef;
     }

     .payment-history-card table.dataTable tbody td {
         vertical-align: middle;
     }

     .payment-history-card .dataTables_length select {
         border-radius: .5rem;
         border: 1px solid #dee2e6;
         padding: .25rem 1.75rem .25rem .6rem;
     }

     .payment-history-card .dataTables_filter input {
         border-radius: 2rem;
         border: 1px solid #dee2e6;
         padding: .4rem .75rem;
         min-width: 260px;
     }

     [data-bs-theme="dark"] .payment-history-card .dataTables_length select,
     [data-bs-theme="dark"] .payment-history-card .dataTables_filter input {
         border-color: #424e5a;
     }

     .payment-history-card .pagination .page-link {
         border: none;
         margin: 0 2px;
         border-radius: 0;
         color: #6658dd;
     }

     .payment-history-card .pagination .page-item.active .page-link {
         background-color: #6658dd;
         color: #fff;
     }

     .payment-history-card .pagination .page-item.disabled .page-link {
         color: #ced4da;
         background: transparent;
     }
 </style>
 <div class="row">
     <div class="col-12">
         <div class="page-title-box">
             <div class="page-title-right">
                 <ol class="breadcrumb m-0">
                     <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/Certification_Portal'); ?>"><?= lang('UI_Text.Certifications') ?></a></li>

                 </ol>
             </div>
             <h4 class="page-title"><?= lang('UI_Text.Payment_History') ?></h4>
         </div>
     </div>
 </div>
 <div class="row">
     <div class="col-lg-12">
         <div class="card payment-history-card">
             <div class="card-body">

                 <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                     <thead>
                         <tr>
                             <th>#</th>
                             <th><?php echo lang('UI_Text.Certification_Name') ?></th>
                             <th><?php echo lang('UI_Text.Price') ?></th>
                             <th><?php echo lang('UI_Text.Transaction_Id') ?></th>
                             <th><?php echo lang('UI_Text.Transaction_Date') ?></th>
                             <th><?php echo lang('UI_Text.Status') ?></th>
                             <!-- <th></th> -->
                         </tr>
                     </thead>
                     <?php $j = 0;
                        foreach ($paymentHistory as $payment) :
                            $j = $j + 1; ?>
                         <tr>
                             <td><?= esc($j) ?></td>
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