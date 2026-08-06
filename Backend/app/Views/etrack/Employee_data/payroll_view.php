<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Payroll Dashboard
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <span style="color:red">Downloaded PDF is password protected. Use PAN Number as password to open.</span>
                <br><br>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Month</th>
                            <th>Year</th>
                           <!--  <th>Details</th> -->
                            <th>Download Payslip</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($payroll as $payslip) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php 
                                $monthNum = $payslip['pay_month']; 
                                $dateObj = DateTime::createFromFormat('!m', $monthNum);
                                echo $dateObj->format('F'); // March
                                ?></td>
                                <td><?php echo $payslip['pay_yr']; ?></td>
                                <!--  <td>
                                    <form class="form-horizontal" action="<?php echo base_url('etrack/Payroll/show_payslip'); ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="payslip_month" value="<?php echo $payslip['pay_month']; ?>">
                                        <input type="hidden" name="payslip_yr" value="<?php echo $payslip['pay_yr']; ?>">
                                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span></button>
                                    </form>
                                </td>  -->
                                <td>
                               
                                    <form class="form-horizontal" action="<?php echo base_url('etrack/Payroll/download_payslip'); ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="payslip_month" value="<?php echo $payslip['pay_month']; ?>">
                                        <input type="hidden" name="payslip_yr" value="<?php echo $payslip['pay_yr']; ?>">
                                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-download"></span></button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>