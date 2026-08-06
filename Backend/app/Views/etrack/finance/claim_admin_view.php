<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Claims Admin
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="searchdatatable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Vendor</th>
                            <th>Claimed By</th>
                            <th>Claim Date</th>

                            <th>Mode</th>
                            <th>USD</th>
                            <th>INR</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($active_claims as $claimx) {
                            $j++;
                            echo '<tr><td>';
                            echo $j;
                            echo '</td><td>';
                            echo  $claimx['vendor_name'];
                            echo '</td><td>';
                            echo $claimx['name'];
                            echo '</td><td>';
                            echo $claimx['requested_on'];
                            echo '</td><td>';
                            switch ($claimx['mode']) {
                                case 1:
                                    echo 'Personal';
                                    break;
                                case 2:
                                    echo 'Srikant CC';
                                    break;
                                case 3:
                                    echo 'Frank CC';
                                    break;
                                case 4:
                                    echo 'Company';
                                    break;
                            }
                            echo '</td><td style="text-align:right">$ ';
                            echo $claimx['claim_amount_usd'];
                            echo '</td><td style="text-align:right">INR ';
                            echo $claimx['claim_amount_inr'];
                            echo '</td><td>';
                            switch ($claimx['status']) {
                                case 1:
                                    echo 'New';
                                    break;
                                case 2:
                                    echo 'Mng Approved';
                                    break;
                                case 3:
                                    echo 'Shrikant Approved';
                                    break;
                                case 4:
                                    echo 'Paid';
                                    break;
                                case 10:
                                    echo 'Rejected';
                                    break;
                            }
                            echo '</td>';

                        ?>

                            <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/view_claim'); ?>" method="POST"><?= csrf_field() ?>
                                <td>
                                    <input type="hidden" name="vd_id" value="<?php echo $claimx['vd_id']; ?>">
                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span></button>
                                </td>
                            </form>
                        <?php
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col-->
</div>