<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/leaves/statement'); ?>">
                            Leave Statement
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                e-Track Leaves
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6 col-md-12">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-0">Leave Balance</h4>
                <div id="cardCollpase1" class="collapse show">
                    <div class="text-center pt-3">
                        <div class="row mt-2">
                            <?php if ($Earned_Leaves[0]['cumulative_leaves'] > 0) { ?>
                                <div class="col-3">
                                    <h3><?php echo $Earned_Leaves[0]['cumulative_leaves']; ?></h3>
                                    <p class="text-muted font-13 mb-0 text-truncate">Earned Leave</p>
                                </div>
                            <?php } ?>
                            <?php if ($Medical_Leaves[0]['cumulative_leaves'] > 0) { ?>
                                <div class="col-3">
                                    <h3><?php echo $Medical_Leaves[0]['cumulative_leaves']; ?></h3>
                                    <p class="text-muted font-13 mb-0 text-truncate">Casual Leave</p>
                                </div>
                            <?php } ?>
                            <?php if ($Restriced_Leaves[0]['cumulative_leaves'] > 0) { ?>
                                <div class="col-3">
                                    <h3><?php echo $Restriced_Leaves[0]['cumulative_leaves']; ?></h3>
                                    <p class="text-muted font-13 mb-0 text-truncate">Restricted Leave</p>
                                </div>
                            <?php } ?>
                            <?php if ($Paternity_Leaves[0]['cumulative_leaves'] > 0) { ?>
                                <div class="col-3">
                                    <h3><?php echo $Paternity_Leaves[0]['cumulative_leaves']; ?></h3>
                                    <p class="text-muted font-13 mb-0 text-truncate">Paternity Leave</p>
                                </div>
                            <?php } ?>
                            <?php if ($Compoff_Leaves[0]['cumulative_leaves'] > 0) { ?>
                                <div class="col-3">
                                    <h3><?php echo $Compoff_Leaves[0]['cumulative_leaves']; ?></h3>
                                    <p class="text-muted font-13 mb-0 text-truncate">Comp off</p>
                                </div>
                            <?php } ?>

                        </div> <!-- end row -->


                    </div>
                </div> <!-- end collapse-->
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    
    </div> <!-- end col-->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/leaves/apply_leaves'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Type of Leave</label>
                        <div class="col-8 col-xl-9">
                            <select name="typeofLeave" class="form-control">

                                <?php if ($Earned_Leaves[0]['cumulative_leaves'] > 0) { ?>
                                    <option value="2">Earned Leaves</option>
                                <?php } ?>
                                <?php if ($Medical_Leaves[0]['cumulative_leaves'] > 0) { ?>
                                    <option value="3">Casual Leaves</option>
                                <?php } ?>
                                <?php if ($Restriced_Leaves[0]['cumulative_leaves'] > 0) { ?>
                                    <option value="4">Restricted Leaves</option>
                                <?php } ?>
                                <?php if ($Paternity_Leaves[0]['cumulative_leaves'] > 0) { ?>
                                    <option value="5">Paternity Leaves</option>
                                <?php } ?>
                                <?php if ($Compoff_Leaves[0]['cumulative_leaves'] > 0) { ?>
                                    <option value="6">Compoff Leaves</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Number of Leaves</label>
                        <div class="col-8 col-xl-9">
                            <input type="number" step=".5" class="form-control" name="numofLeaves" value="" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Start date</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="start_date" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Remarks</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="remarks" value="">
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <input type="hidden" name="earned_balance" value="<?php echo $Earned_Leaves[0]['cumulative_leaves']; ?>">
                            <input type="hidden" name="medical_balance" value="<?php echo $Medical_Leaves[0]['cumulative_leaves']; ?>">
                            <input type="hidden" name="restriced_balance" value="<?php echo $Restriced_Leaves[0]['cumulative_leaves']; ?>">
                            <input type="hidden" name="paternity_balance" value="<?php echo $Paternity_Leaves[0]['cumulative_leaves']; ?>">
                            <input type="hidden" name="compoff_balance" value="<?php echo $Compoff_Leaves[0]['cumulative_leaves']; ?>">
                            <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
                                Apply Leaves
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>