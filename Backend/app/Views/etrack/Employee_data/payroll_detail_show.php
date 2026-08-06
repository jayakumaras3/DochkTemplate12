<div class="row">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <?php if ($return_page == 1) { ?>
                                <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/Payroll'); ?>">
                                        Payroll Dashboard
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($return_page == 2) { ?>
                                <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/personal'); ?>">
                                        Admin HR Personal Data
                                    </a>
                                </li>
                            <?php } ?>

                        </ol>
                    </div>
                    <h4 class="page-title">PAYSLIP</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <?php
            function getIndianCurrency(float $number)
            {
                $decimal = round($number - ($no = floor($number)), 2) * 100;
                $hundred = null;
                $digits_length = strlen($no);
                $i = 0;
                $str = array();
                $words = array(
                    0 => '',
                    1 => 'One',
                    2 => 'Two',
                    3 => 'Three',
                    4 => 'Four',
                    5 => 'Five',
                    6 => 'Six',
                    7 => 'Seven',
                    8 => 'Eight',
                    9 => 'Nine',
                    10 => 'Ten',
                    11 => 'Eleven',
                    12 => 'Twelve',
                    13 => 'Thirteen',
                    14 => 'Fourteen',
                    15 => 'Fifteen',
                    16 => 'Sixteen',
                    17 => 'Seventeen',
                    18 => 'Eighteen',
                    19 => 'Nineteen',
                    20 => 'Twenty',
                    30 => 'Thirty',
                    40 => 'Forty',
                    50 => 'Fifty',
                    60 => 'Sixty',
                    70 => 'Seventy',
                    80 => 'Eighty',
                    90 => 'Ninety'
                );
                $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
                while ($i < $digits_length) {
                    $divider = ($i == 2) ? 10 : 100;
                    $number = floor($no % $divider);
                    $no = floor($no / $divider);
                    $i += $divider == 10 ? 1 : 2;
                    if ($number) {
                        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                        $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
                    } else $str[] = null;
                }
                $Rupees = implode('', array_reverse($str));
                $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
                return ($Rupees ? $Rupees : '') . $paise;
            }
            ?>
            <div class="col-sm-12">

                <!-- Logo & title -->
                <div class="clearfix mt-4 mb-4">
                    <div class="float-start">
                        <div class="logo logo-dark">
                            <span class="logo-lg">
                                <img src="<?php echo base_url('assets/assets/uploads/client_logo/touchstone_logo.png'); ?>" alt="" height="30px">
                            </span>
                        </div>
                    </div>
                    <div class="float-end">
                        <h5 class="m-0">PAYSLIP - <?php echo $getpayroll_details[0]['ID'] * 232; ?></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12  mt-2">
                        <div class="text-center">
                            <h3><strong>Talentquest Solutions Pvt. Ltd.</strong></h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12  mt-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-0"><strong>Employee Information</strong></h5>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div>
                <div class="row">

                    <div class="col-sm-6">

                        <p><strong>Employee Name : </strong> <span class="float-end"> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $getpayroll_details[0]['fname'] . ' ' . $getpayroll_details[0]['last_name']; ?></span></br>
                            <strong>Designation : </strong> <span class="float-end">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $getpayroll_details[0]['designation']; ?></span></br>
                            <strong>PAN : </strong> <span class="float-end"><?php echo $getpayroll_details[0]['pan']; ?> </span></br>
                            <strong>Bank : </strong> <span class="float-end"><?php echo $getpayroll_details[0]['bank_name']; ?> </span></br>
                            <strong>Leave Without Pay : </strong> <span class="float-end"> <?php echo $getpayroll_details[0]['lop']; ?></span>
                        </p>

                    </div><!-- end col -->

                    <div class="col-sm-6">

                        <p><strong>Employee ID : </strong> <span class="float-end"> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $getpayroll_details[0]['user_id']; ?></span></br>
                            <strong>Date of Joining : </strong> <span class="float-end">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $getpayroll_details[0]['DOJ']; ?></span></br>
                            <strong>UAN : </strong> <span class="float-end"><?php echo $getpayroll_details[0]['uan']; ?> </span></br>
                            <strong>Account Number : </strong> <span class="float-end"><?php echo $getpayroll_details[0]['account_num']; ?> </span></br>
                            <strong>Total Working Days : </strong> <span class="float-end"> <?php echo $getpayroll_details[0]['working_days']; ?></span>
                        </p>

                    </div><!-- end col -->

                </div>

                <!-- end row -->

                <div class="row">
                    <div class="col-sm-12  mt-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-0"><strong>Breakup : <?php
                                                                                $monthNum = $getpayroll_details[0]['pay_month'];
                                                                                $dateObj = DateTime::createFromFormat('!m', $monthNum);
                                                                                echo $dateObj->format('F');
                                                                                echo ' ' . $getpayroll_details[0]['pay_yr']; ?></strong></h5>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div>


                <div class="row">
                    <?php
                    $allowance = array(
                        'Basic' => 'basic',
                        'HRA' => 'hra',
                        'Conveyance' => 'conv',
                        'Education' => 'edu_allowance',
                        'LTA' => 'lta',
                        'Meal Allowance' => 'meal_allowance',
                        'Medical Allowance' => 'medical_allwance',
                        'Internet Allowance' => 'car_lease_allowance',
                        'Flexible Allowance' => 'flexi_allowance',
                        'Arrears / Others' => 'arrears_others'
                    );

                    $deduction = array(
                        'Professional Tax' => 'pt',
                        'PF Employee' => 'pf_ee',
                        'PF Employer' => 'pf_er',
                        'ESI' => 'esi',
                        'Salary Advance & other deductions' => 'sal_adv_other_deduct',
                        'Other Deduction' => 'other_deduct',
                        'Late coming Deductions' => 'late_come_deduct',
                        'Income Tax' => 'income_tax',
                        'Sodexo Coupon' => 'sodexo',
                        'VFP Employee' => 'VFPEmployee'
                    );

                    ?>
                    <div class="col-sm-6">

                        <table class="table  table-centered">

                            <thead>
                                <th>#</th>
                                <th>Allowance</th>
                                <th>Amount</th>
                            </thead>
                            <tbody>
                                <?php
                                $j = 0;
                                $totalal = 0;
                                foreach ($allowance as $x => $y) {
                                    $value = $getpayroll_details[0][$y];
                                    if ($value > 0) {
                                        $totalal = $totalal + $value;
                                        $j++;
                                        echo '<tr style="height:10px !important;"><td>';
                                        echo $j;
                                        echo '</td><td>';
                                        echo $x;
                                        echo '</td><td><span class="float-end">';
                                        echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $value);
                                        echo '</span></td></tr>';
                                    }
                                }
                                echo '<tr><td></td><td><strong>TOTAL ALLOWANCE</strong></td><td><span class="float-end"><strong>';
                                echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $totalal);
                                echo '</strong></span></td></tr>';
                                ?>
                            </tbody>
                        </table>
                    </div> <!-- end col -->
                    <div class="col-sm-6">

                        <table class="table table-centered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Deduction</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $j = 0;
                                $totalal = 0;
                                foreach ($deduction as $x => $y) {
                                    $value = $getpayroll_details[0][$y];
                                    if ($value > 0) {
                                        $totalal = $totalal + $value;
                                        $j++;
                                        echo '<tr><td>';
                                        echo $j;
                                        echo '</td><td>';
                                        echo $x;
                                        echo '</td><td><span class="float-end">';
                                        echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $value);
                                        echo '</span></td></tr>';
                                    }
                                }
                                echo '<tr><td></td><td><strong>TOTAL DEDUCTION</strong></td><td><span class="float-end"><strong>';
                                echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $totalal);
                                echo '</strong></span></td></tr>';
                                ?>

                            </tbody>
                        </table>

                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-sm-12">

                        <span>
                            <h4>Net Pay: INR <?php $net = $getpayroll_details[0]['net_pay_amount'];
                                                echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $net) . '/-';
                                                ?></h4>
                        </span>
                        <p><b>Net Pay (in words):</b> <?php echo getIndianCurrency($net); ?></p>


                        <div class="clearfix"></div>
                        <h6 class="text-muted">Notes:</h6>
                        <p></p>
                    </div> <!-- end col -->
                </div>
                <div class="row">
                    <div class="col-sm-12  mt-2">
                        <div class="text-center ">
                            <p class="text-danger">Copyright © <?php echo date('Y'); ?> TSLAC Solutions Private limited All rights Reserved.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 mt-1">
                        <div class="text-center">
                            <small class="text-muted">
                                <?php
                                $info = 'This is a computer-generated document and it does not require a signature. This document shall not be invalidated solely on the ground that it is not signed.';
                                echo strtoupper($info);
                                ?>
                            </small>
                        </div>
                    </div> <!-- end col -->

                </div>
                <br>
                <br>
                <!-- end row -->
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->