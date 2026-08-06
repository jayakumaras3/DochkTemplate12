<html>

<head>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 20px;
            padding: 25px;
            padding-top: 110px;
            position: relative;
            min-height: 100vh;
        }

        header {
            position: fixed;
            top: 1cm;
            left: 0cm;
            right: 0cm;
            height: auto;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: black;
            margin-top: 20px;
            margin-bottom: 20px;
            border-spacing: 10px;
        }

        td {
            vertical-align: top;
            padding: 5px;

        }

        .rightalign {
            text-align: right;
            float: right;
        }
    </style>
</head>

<body>
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
    <header>
        <table>
            <thead>
                <tr>
                    <th style="text-align: left; ">
                        <img src="<?php echo $header_img; ?>" width="200px" style="padding-left: 50px;" />
                    </th>
                    <th>
                        <h5>PAYSLIP - <?php echo $getpayroll_details[0]['pay_month'] . $getpayroll_details[0]['pay_yr'] . $getpayroll_details[0]['ID']; ?></h5>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <?php
                        if ($dateofpay < '2025-04-01') {
                        ?>
                            <h1 style="color:rgb(9, 49, 81);"><strong>Talentquest Solutions Pvt. Ltd.</strong></h1>
                        <?php
                        } else {
                        ?>
                            <h1 style="color:rgb(9, 49, 81);"><strong>TSLAC Solutions Pvt. Ltd.</strong></h1>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </header>
    <br><br><br><br>

    <table>
        <thead>
            <tr style="background-color: #d2d2d2;">
                <th colspan="4">
                    <h3>Employee Information</h3>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Employee Name :</td>
                <td><?php echo $getpayroll_details[0]['fname'] . ' ' . $getpayroll_details[0]['last_name']; ?></td>
                <td>Employee ID :</td>
                <td><?php echo $getpayroll_details[0]['user_id']; ?></td>
            </tr>
            <tr>
                <td>Designation :</td>
                <td><?php echo $getpayroll_details[0]['designation']; ?></td>
                <td>Date of Joining :</td>
                <td><?php echo $getpayroll_details[0]['DOJ']; ?></td>
            </tr>
            <tr>
                <td>PAN :</td>
                <td><?php echo $getpayroll_details[0]['pan']; ?></td>
                <td>UAN :</td>
                <td><?php echo $getpayroll_details[0]['uan']; ?></td>
            </tr>
            <tr>
                <td>Bank :</td>
                <td><?php echo $getpayroll_details[0]['bank_name']; ?></td>
                <td>Account Number :</td>
                <td><?php echo $getpayroll_details[0]['account_num']; ?></td>
            </tr>
            <tr>
                <td>Leave Without Pay :</td>
                <td><?php echo $getpayroll_details[0]['lop']; ?></td>
                <td>Total Working Days :</td>
                <td><?php echo $getpayroll_details[0]['working_days']; ?></td>
            </tr>
        </tbody>
    </table>


    <table>
        <thead>
            <tr style="background-color: #d2d2d2;">
                <th>
                    <h3><strong>Breakup : <?php
                                            $monthNum = $getpayroll_details[0]['pay_month'];
                                            $dateObj = DateTime::createFromFormat('!m', $monthNum);
                                            echo $dateObj->format('F');
                                            echo ' ' . $getpayroll_details[0]['pay_yr']; ?></strong></h3>
                </th>
            </tr>
        </thead>
    </table>
    <!-- end row -->



    <?php
    $allowance = array(
        'Basic' => 'basic',
        'HRA' => 'hra',
        'Conveyance' => 'conv',
        'Education' => 'edu_allowance',
        'LTA' => 'lta',
        'Meal Allowance' => 'meal_allowance',
        'Internet Allowance ' => 'medical_allwance',
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

    <table>
        <thead>
            <tr>
                <th>
                    ALLOWANCE
                </th>
                <th>
                    DEDUCTION
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <table>
                        <thead>
                            <tr style="background-color: #c6d7e8; height: 40px;">
                                <th style="padding: 5px;">#</th>
                                <th style="text-align: left; padding: 5px;">Item</th>
                                <th style="padding: 5px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;
                            $totalal = 0;
                            if ($getpayroll_details[0]['pf_er']) {
                                $emppf = $getpayroll_details[0]['pf_er'];
                            } else {
                                $emppf = 0;
                            }
                            foreach ($allowance as $x => $y) {
                                $value = $getpayroll_details[0][$y];
                                if ($value > 0) {
                                    $totalal = $totalal + $value;
                                    $j++;
                                    echo '<tr style="height:10px !important; border-bottom: 1px solid #5A5A5A;"><td>';
                                    echo $j;
                                    echo '</td><td>';
                                    echo $x;
                                    echo '</td><td><span class="rightalign">';
                                    echo '<img src="' . $rupee_sym . '" width="6px" /> ';
                                    echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $value);
                                    echo '</span></td></tr>';
                                }
                            }
                            echo '<tr style="height:10px !important; border-bottom: 1px solid #5A5A5A;"><td></td><td><strong>GROSS SALARY</strong></td><td><span class="rightalign"><strong>';
                            echo '<img src="' . $rupee_sym . '" width="6px" /> ';
                            echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $totalal);
                            echo '</strong></span></td></tr>';

                            echo '<tr style="height:10px !important; border-bottom: 1px solid #5A5A5A;"><td></td><td>PF Employer Contribution</td><td><span class="rightalign">';
                            echo '<img src="' . $rupee_sym . '" width="6px" /> ';
                            echo $emppf;
                            echo '</span></td></tr>';

                            echo '<tr style="height:10px !important; border-bottom: 1px solid #5A5A5A;"><td></td><td><strong>CTC</strong></td><td><span class="rightalign"><strong>';
                            echo '<img src="' . $rupee_sym . '" width="6px" /> ';
                            echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $totalal + $emppf);
                            echo '</strong></span></td></tr>';


                            ?>
                        </tbody>
                    </table>
                </td>
                <td>
                    <table>
                        <thead>
                            <tr style="background-color: #c6d7e8; height: 40px;">
                                <th style="padding: 5px;">#</th>
                                <th style="text-align: left; padding: 5px;">Item</th>
                                <th style="padding: 5px;">Amount</th>
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
                                    echo '<tr style="height:10px !important; border-bottom: 1px solid #5A5A5A;"><td>';
                                    echo $j;
                                    echo '</td><td>';
                                    echo $x;
                                    echo '</td><td><span class="rightalign" >';
                                    echo '<img src="' . $rupee_sym . '" width="6px" /> ';
                                    echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $value);
                                    echo '</span></td></tr>';
                                }
                            }
                            echo '<tr style="height:10px !important; border-bottom: 1px solid #5A5A5A;"><td></td><td><strong>TOTAL DEDUCTION</strong></td><td><span class="rightalign"><strong>';
                            echo '<img src="' . $rupee_sym . '" width="6px" /> ';
                            echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $totalal);
                            echo '</strong></span></td></tr>';
                            ?>

                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
            <tr style="background-color: #c6d7e8; height: 20px;">
                <td>
                    <b>Net Pay:</b> <span style="font-size: 20px; font-weight:bold; "><img src="<?php echo $rupee_sym; ?>" width="8px" /> <?php $net = $getpayroll_details[0]['net_pay_amount'];
                                                                                                                                            echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $net) . '/-';
                                                                                                                                            ?></span>

                </td>
            </tr>
            <tr>
                <td>
                    <?php echo getIndianCurrency($net); ?> Rupees Only
                </td>
            </tr>
            <?php
            if (strlen($getpayroll_details[0]['note'])) {
            ?>
                <tr>
                    <td>
                        <h6>Notes:</h6>
                        <p>
                            <?php
                            echo  $getpayroll_details[0]['note'];
                            ?>
                        </p>
                    </td>
                </tr>
            <?php
            } ?>

        </tbody>
    </table>
    <table>
        <tbody>
            <tr>
                <td>
                    <?php
                    if ($dateofpay < '2025-04-01') {
                    ?>
                        <p class="text-danger">Copyright © <?php echo date('Y'); ?> Talentquest Solutions Private limited All rights Reserved.</p>

                    <?php
                    } else {
                    ?>
                        <p class="text-danger">Copyright © <?php echo date('Y'); ?> TSLAC Solutions Private limited All rights Reserved.</p>

                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <small style="font-size: 8px;">
                        <?php
                        $info = 'This is a computer-generated document and it does not require a signature. This document shall not be invalidated solely on the ground that it is not signed.';
                        echo strtoupper($info);
                        ?>
                    </small>
                </td>
            </tr>

        </tbody>
    </table>
</body>

</html>