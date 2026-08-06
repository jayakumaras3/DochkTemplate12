<style>
    .leave-instructions-card {
        position: relative;
        overflow: hidden;
        border: none;
        border-radius: 16px;
        background: rgba(var(--ct-primary-rgb), 0.06);
    }

    .leave-instructions-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: rgba(var(--ct-primary-rgb), 0.12);
        color: var(--ct-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .leave-instructions-title {
        color: var(--ct-primary);
        font-weight: 700;
        margin-bottom: .5rem;
    }

    .leave-instructions-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .leave-instructions-list li {
        display: flex;
        align-items: flex-start;
        gap: .6rem;
        margin-bottom: .4rem;
    }

    .leave-instructions-list li:last-child {
        margin-bottom: 0;
    }

    .leave-check-icon {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: var(--ct-primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        flex-shrink: 0;
        margin-top: .15rem;
    }

    .leave-instructions-deco {
        display: none;
        position: absolute;
        right: -10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 6rem;
        color: rgba(var(--ct-primary-rgb), 0.12);
    }

    @media (min-width: 992px) {
        .leave-instructions-deco {
            display: block;
        }
    }

    .leave-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .leave-card-header {
        display: flex;
        align-items: center;
        gap: .65rem;
        margin-bottom: 1rem;
    }

    .leave-card-header .leave-icon-badge {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .leave-card-header h4 {
        margin-bottom: 0;
    }

    .leave-balance-tile {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-radius: 14px;
        padding: 1rem 1.1rem;
        height: 100%;
    }

    .leave-balance-value {
        font-weight: 800;
        margin-bottom: .15rem;
    }

    .leave-balance-label {
        margin-bottom: 0;
        font-size: .8rem;
        opacity: .75;
    }

    .leave-balance-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .leave-form-group {
        margin-bottom: 1rem;
    }

    .leave-form-group label {
        display: block;
        font-weight: 600;
        font-size: .85rem;
        margin-bottom: .4rem;
    }

    .leave-form-group .form-control,
    .leave-form-group .form-select {
        border-radius: 10px;
        padding: .6rem .9rem;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Leave
            </h4>
        </div>
    </div>
</div>
<?php
// Tile colour/icon per leave type - cycled so every visible balance tile reads distinctly.
$leaveTilePalette = [
    ['bg' => 'bg-soft-primary', 'text' => 'text-primary', 'icon' => 'mdi-calendar-month-outline'],
    ['bg' => 'bg-soft-success', 'text' => 'text-success', 'icon' => 'mdi-beach'],
    ['bg' => 'bg-soft-warning', 'text' => 'text-warning', 'icon' => 'mdi-star-outline'],
    ['bg' => 'bg-soft-info', 'text' => 'text-info', 'icon' => 'mdi-account-heart-outline'],
    ['bg' => 'bg-soft-danger', 'text' => 'text-danger', 'icon' => 'mdi-swap-horizontal'],
    ['bg' => 'bg-soft-secondary', 'text' => 'text-secondary', 'icon' => 'mdi-heart-outline'],
];
$leaveTiles = [];
if ($Earned_Leaves[0]['cumulative_leaves'] > 0) {
    $leaveTiles[] = ['value' => $Earned_Leaves[0]['cumulative_leaves'], 'label' => 'Earned Leave'];
}
if ($Medical_Leaves[0]['cumulative_leaves'] > 0) {
    $leaveTiles[] = ['value' => $Medical_Leaves[0]['cumulative_leaves'], 'label' => 'Casual Leave'];
}
if ($Restriced_Leaves[0]['cumulative_leaves'] > 0) {
    $leaveTiles[] = ['value' => $Restriced_Leaves[0]['cumulative_leaves'], 'label' => 'Restricted Leave'];
}
if ($Paternity_Leaves[0]['cumulative_leaves'] > 0) {
    $leaveTiles[] = ['value' => $Paternity_Leaves[0]['cumulative_leaves'], 'label' => 'Paternity Leave'];
}
if ($Compoff_Leaves[0]['cumulative_leaves'] > 0) {
    $leaveTiles[] = ['value' => $Compoff_Leaves[0]['cumulative_leaves'], 'label' => 'Comp off'];
}
if ($gender == 1 && $Menasural_Leaves_Taken == 0) {
    $leaveTiles[] = ['value' => 1, 'label' => 'Casual Leave +'];
}
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card leave-card">
            <div class="card-body">
                <div class="leave-card-header">
                    <span class="leave-icon-badge bg-soft-info text-info"><i class="mdi mdi-file-document-edit-outline"></i></span>
                    <h4>Apply Leave</h4>
                    
                </div>
                <form action="<?php echo base_url('etrack/leaves/apply_leaves'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="leave-form-group">
                        <label for="typeofLeave">Type of Leave</label>
                        <select id="typeofLeave" name="typeofLeave" class="form-select">

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
                            <?php if ($gender == 1) {
                                if ($Menasural_Leaves_Taken == 0) {
                            ?>
                                    <option value="7">Casual Leave +</option>
                            <?php }
                            } ?>
                            <option value="8">Leave Without Pay</option>
                            <option value="9">On Duty</option>
                        </select>
                    </div>
                    <div class="leave-form-group">
                        <label for="apply_numofLeaves">Number of Leaves</label>
                        <select id="apply_numofLeaves" name="numofLeaves" class="form-select">
                            <option value=".5" selected>Half Day</option>
                            <option value="1">1 Day</option>
                            <option value="2">2 Days</option>
                            <option value="3">3 Days</option>
                            <option value="4">4 Days</option>
                            <option value="5">5 Days</option>
                        </select>
                    </div>

                    <div class="leave-form-group">
                        <label for="start_date">Start Date</label>
                        <input id="start_date" name="start_date" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                        <script>
                            function timeFunctionLong(input) {
                                setTimeout(function() {
                                    input.type = 'text';
                                }, 60000);
                            }
                        </script>
                    </div>
                    <div class="leave-form-group">
                        <label for="apply_remarks">Remarks</label>
                        <textarea id="apply_remarks" class="form-control" name="remarks" rows="3" placeholder="Enter remarks (optional)"></textarea>
                    </div>
                    <input type="hidden" name="earned_balance" value="<?php echo $Earned_Leaves[0]['cumulative_leaves']; ?>">
                    <input type="hidden" name="medical_balance" value="<?php echo $Medical_Leaves[0]['cumulative_leaves']; ?>">
                    <input type="hidden" name="restriced_balance" value="<?php echo $Restriced_Leaves[0]['cumulative_leaves']; ?>">
                    <input type="hidden" name="paternity_balance" value="<?php echo $Paternity_Leaves[0]['cumulative_leaves']; ?>">
                    <input type="hidden" name="compoff_balance" value="<?php echo $Compoff_Leaves[0]['cumulative_leaves']; ?>">
                    <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-primary rounded-pill waves-effect waves-light">
                        <i class="mdi mdi-send-outline me-1"></i>Apply Leave
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card leave-instructions-card">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="leave-instructions-icon">
                    <i class="mdi mdi-clipboard-search-outline"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="leave-instructions-title">Important Instructions</h5>
                    <ul class="leave-instructions-list">
                        <li>
                            <span class="leave-check-icon"><i class="mdi mdi-check"></i></span>
                            <span>It is mandatory to apply leave on or before 26th of each month for payroll processing.</span>
                        </li>
                        <li>
                            <span class="leave-check-icon"><i class="mdi mdi-check"></i></span>
                            <span>Leaves are credited in advance at the start of each quarter. In case an employee resigns and has availed leaves from the advance balance, it will be adjusted in the full and final settlement.</span>
                        </li>
                        <li>
                            <span class="leave-check-icon"><i class="mdi mdi-check"></i></span>
                            <span>For leave eligibility, please refer the policy document or contact HR.</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div> <!-- end card-->

        <div class="card leave-card">
            <div class="card-body">
                <div class="leave-card-header">
                    <span class="leave-icon-badge bg-soft-primary text-primary"><i class="mdi mdi-chart-bar"></i></span>
                    <h4>Leave Balance</h4>
                    <a href="<?php echo base_url('etrack/leaves/statement'); ?>" class="ms-auto text-muted" title="Leave Statement">
                        <span class="leave-icon-badge bg-soft-warning text-warning"><i class="mdi mdi-microsoft-excel font-20"></i></span> Statement
                    </a>
                </div>
                <div class="row g-3">
                    <?php foreach ($leaveTiles as $i => $tile):
                        $palette = $leaveTilePalette[$i % count($leaveTilePalette)];
                    ?>
                        <div class="col-6">
                            <div class="leave-balance-tile <?= $palette['bg'] ?>">
                                <div>
                                    <h2 class="leave-balance-value <?= $palette['text'] ?>"><?= $tile['value'] ?></h2>
                                    <p class="leave-balance-label"><?= $tile['label'] ?></p>
                                </div>
                                <div class="leave-balance-icon bg-white <?= $palette['text'] ?>">
                                    <i class="mdi <?= $palette['icon'] ?>"></i>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->

        <!-- Request Leave / Comp Off -->
        <div class="card leave-card">
            <div class="card-body">
                <div class="leave-card-header">
                    <span class="leave-icon-badge bg-soft-success text-success"><i class="mdi mdi-account-multiple-outline"></i></span>
                    <h4>Request Comp Off</h4>
                </div>
                <form action="<?php echo base_url('etrack/leaves/apply_compoff'); ?>" method="POST"><?= csrf_field() ?>
                  
                    <input type="hidden" name="requested_to" value="1">
                    <div class="leave-form-group">
                        <label for="compoff_numofLeaves">Number of Leaves</label>
                        <select id="compoff_numofLeaves" name="numofLeaves" class="form-select">
                            <option value=".5" selected>Half Day</option>
                            <option value="1">1 Day</option>
                            <option value="2">2 Days</option>
                            <option value="3">3 Days</option>
                            <option value="4">4 Days</option>
                            <option value="5">5 Days</option>
                        </select>
                    </div>
                    <div class="leave-form-group">
                        <label for="compoff_remarks">Remarks</label>
                        <input id="compoff_remarks" type="text" class="form-control" name="remarks" placeholder="Enter remarks" value="">
                    </div>
                    <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger rounded-pill waves-effect waves-light">
                        <i class="mdi mdi-briefcase-clock-outline me-1"></i>Request Comp Off
                    </button>
                </form>
            </div>
        </div>
    </div>
    
</div>