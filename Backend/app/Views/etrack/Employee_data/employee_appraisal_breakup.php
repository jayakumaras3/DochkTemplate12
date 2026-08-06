<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if ($return_page == 1) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/employee_details/appraisals'); ?>">
                                Appraisals
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($return_page == 2) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/view_appraisal_data'); ?>">
                                Appraisal Data
                            </a>
                        </li>



                    <?php
                    } ?>
                    <?php if ($return_page == 3) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/view_all_appraisals'); ?>">
                                Appraisal Data By Date
                            </a>
                        </li>
                    <?php } ?>
                </ol>
            </div>
            <h4 class="page-title">Appraisal Breakup</h4>
        </div>
    </div>
</div>
<?php if (count($appraisal_breakup) > 0) { ?>
    <div class="row">
        <div class="col-md-12">
            <!-- Logo & title -->
            <div class="card">
                <div class="card-body">
                    <div class="logo logo-dark">
                        <span class="logo-lg">
                            <img src="<?php echo base_url('assets/assets/uploads/client_logo/touchstone_logo.png'); ?>" alt="" height="30px">
                        </span>
                    </div>

                    <div class="text-center mb-2">
                        <h3><strong><u>Private and Confidential</u></strong></h3><br><br>
                    </div>
                    <p><strong>Employee Name : </strong> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $appraisal_breakup[0]['fname'] . ' ' . $appraisal_breakup[0]['last_name']; ?></br>
                        <strong>Employee ID : </strong> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $appraisal_breakup[0]['emp_id']; ?></br>
                    </p>

                    <div class="text-center  mt-2">
                        <h3><strong><u>Salary Breakup</u></strong></h3><br><br>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Earnings</th>
                                        <td></td>
                                        <th style="text-align: right;">Per Month</th>
                                        <th style="text-align: right;">Per Annum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Basic & DA</td>
                                        <td></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['basic']); ?></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['basic'] * 12); ?></td>
                                    </tr>
                                    <tr>
                                        <td>HRA</td>
                                        <td></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['hra']); ?></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['hra'] * 12); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Education Allowance</td>
                                        <td></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['edu_allowance']); ?></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['edu_allowance'] * 12); ?></td>
                                    </tr>
                                    <tr>
                                        <td>LTA</td>
                                        <td></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['lta']); ?></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['lta'] * 12); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Meal Allowance</td>
                                        <td></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['meal_allow']); ?></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['meal_allow'] * 12); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Internet Allowance</td>
                                        <td></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['internet_allow']); ?></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['internet_allow'] * 12); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Flexible Allowances</td>
                                        <td></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['flex_allow']); ?></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['flex_allow'] * 12); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Gross Salary</th>
                                        <th></th>
                                        <th style="text-align: right;"><?php

                                                                        $totalgross = $appraisal_breakup[0]['basic'] + $appraisal_breakup[0]['hra'] + $appraisal_breakup[0]['edu_allowance'] + $appraisal_breakup[0]['lta'] + $appraisal_breakup[0]['meal_allow'] + $appraisal_breakup[0]['internet_allow'] + $appraisal_breakup[0]['flex_allow'];

                                                                        echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $totalgross); ?></th>
                                        <th style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $totalgross * 12); ?></th>
                                    </tr>
                                    <tr>
                                        <td>Add: Employer Contribution to PF</td>
                                        <td></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['emp_pf']); ?></td>
                                        <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['emp_pf'] * 12); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Cost to the Company (CTC)</th>
                                        <th></th>
                                        <th style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['emp_pf'] + $totalgross); ?></th>
                                        <th style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['emp_pf'] * 12 + $totalgross * 12); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Deduction</th>
                                        <th></th>
                                        <th style="text-align: right;">Per Month</th>
                                        <th style="text-align: right;">Per Annum</th>
                                    </tr>
                                </thead>

                                <tr>
                                    <td>Professional Tax</td>
                                    <td></td>
                                    <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['prof_tax']); ?></td>
                                    <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['prof_tax'] * 12); ?></td>
                                </tr>
                                <tr>
                                    <td>Provident Fund</td>
                                    <td>Employee</td>
                                    <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['emp_pf']); ?></td>
                                    <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['emp_pf'] * 12); ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Employer</td>
                                    <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['empl_pf']); ?></td>
                                    <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['empl_pf'] * 12); ?></td>
                                </tr>
                                <tr>
                                    <td>ESI </td>
                                    <td></td>
                                    <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['esi']); ?></td>
                                    <td style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['esi'] * 12); ?></td>
                                </tr>

                                <tr>
                                    <td colspan="4">Less TDS<br>
                                        <p class="text-danger">(Subject to submission of proof and law applicable at the time of disbusement of salary) </p>
                                    </td>

                                </tr>
                                <tr>
                                    <td>Total Deduction</td>
                                    <td></td>
                                    <th style="text-align: right;"><?php

                                                                    $totaldeduct = $appraisal_breakup[0]['prof_tax'] + $appraisal_breakup[0]['emp_pf'] + $appraisal_breakup[0]['empl_pf'] + $appraisal_breakup[0]['esi'];

                                                                    echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $totaldeduct); ?></th>
                                    <th style="text-align: right;"><?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $totaldeduct * 12); ?></th>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <p style="font-size: large; font-weight:bold; ">
                        Net Salary : INR <?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['emp_pf'] + $totalgross - $totaldeduct); ?>
                    </p>
                    <p>
                        <strong>CTC including ESI </strong>
                    </p>
                    <p>
                        Employer Contribution to ESI : INR <?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['empl_esi']); ?>
                        <br>
                        TOTAL CTC including PF & ESI : INR <?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $appraisal_breakup[0]['emp_pf'] + $totalgross - $totaldeduct + $appraisal_breakup[0]['empl_esi']); ?>
                    </p>

                    <p>
                        Other Benefits:
                    <ol type="a">
                        <li>ESI Contribution to those employees who are eligible under the ESI Scheme</li>
                        <li>Medical Insurance of Rs.3,00,000 coverage as per the policy of the Company </li>
                        <li>Personal Accident Insurance of Rs.5,00,000 covarage as per policy of the Company</li>
                        <li>Gratuity as per the Provision of the Payment of Gratuity Act, 1972</li>
                    </ol>


                    </p>
                    <p>If any error recorded, please inform Finance team. </p>
                </div>
            </div><!-- end col -->
        </div>
    </div>



    <?php
    $userlevel = session('userlevel');
    $id_user = session('id_user');
    $arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
    if (in_array('2010', $arrayuserlevel)) {
    ?>
        <div class="row">
            <div class="col-md-12">
                <!-- Portlet card -->
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/udpate_breakup'); ?>" method="POST"><?= csrf_field() ?>
                            <div class="row mb-3">
                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Basic & DA</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="basic" class="form-control" value="<?php echo $appraisal_breakup[0]['basic']; ?>">
                                    </div>
                                </div>

                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">HRA</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="hra" class="form-control" value="<?php echo $appraisal_breakup[0]['hra']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Education Allowance</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="edu_allowance" class="form-control" value="<?php echo $appraisal_breakup[0]['edu_allowance']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">LTA</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="lta" class="form-control" value="<?php echo $appraisal_breakup[0]['lta']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Meal Allowance</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="meal_allow" class="form-control" value="<?php echo $appraisal_breakup[0]['meal_allow']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Internet Allowance</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="internet_allow" class="form-control" value="<?php echo $appraisal_breakup[0]['internet_allow']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Flexible Allowance</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="flex_allow" class="form-control" value="<?php echo $appraisal_breakup[0]['flex_allow']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Employee PF</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="emp_pf" class="form-control" value="<?php echo $appraisal_breakup[0]['emp_pf']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Employer PF</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="empl_pf" class="form-control" value="<?php echo $appraisal_breakup[0]['empl_pf']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Professional Tax</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="prof_tax" class="form-control" value="<?php echo $appraisal_breakup[0]['prof_tax']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">ESI</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="esi" class="form-control" value="<?php echo $appraisal_breakup[0]['esi']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Employer ESI</label>
                                    <div class="col-8 col-xl-9">
                                        <input type="text" name="empl_esi" class="form-control" value="<?php echo $appraisal_breakup[0]['empl_esi']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 mt-4 mb-2">
                                    <div class="col-8 col-xl-9">
                                        <input type="hidden" name="salid" value="<?php echo $appraisal_breakup[0]['salid']; ?>">
                                        <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                            Update Breakup
                                        </button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end col-->
        </div>
    <?php } ?>
<?php } ?>