<?php
// Pre-filter the "No Data" list once so we can both count it and cap its display.
$ignorelist = array(1141, 1138, 1238, 1237, 1202, 1135, 1239, 1203);
$nodata_employees = [];
foreach ($all_employees as $missing) {
    $iduser = $missing['id_user'];
    if (array_search($iduser, array_column($workfromhome, 'id_user')) !== false) {
        continue;
    }
    if (array_search($iduser, array_column($inofficetoday, 'id_user')) !== false) {
        continue;
    }
    if (array_search($iduser, array_column($leave, 'id_user')) !== false) {
        continue;
    }
    if (in_array($iduser, $ignorelist)) {
        continue;
    }
    $nodata_employees[] = $missing;
}
$visible_row_limit = 5;
?>
<style>
    .status-tile {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
        border-bottom: 4px solid transparent;
        position: relative;
    }

    .status-tile.tile-office {
        border-bottom-color: #6658dd;
    }

    .status-tile.tile-wfh {
        border-bottom-color: #0acf97;
    }

    .status-tile.tile-leave {
        border-bottom-color: #33a9e0;
    }

    .status-tile .status-tile-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .status-tile.tile-office .status-tile-icon {
        background: rgba(102, 88, 221, .12);
        color: #6658dd;
    }

    .status-tile.tile-wfh .status-tile-icon {
        background: rgba(10, 207, 151, .12);
        color: #0acf97;
    }

    .status-tile.tile-leave .status-tile-icon {
        background: rgba(51, 169, 224, .12);
        color: #33a9e0;
    }

    .status-tile h2 {
        font-weight: 700;
        margin-bottom: 0;
    }

    .status-tile.tile-office h2 {
        color: #6658dd;
    }

    .status-tile.tile-wfh h2 {
        color: #0acf97;
    }

    .status-tile.tile-leave h2 {
        color: #33a9e0;
    }

    .status-tile-illustration {
        position: absolute;
        right: 1rem;
        bottom: .5rem;
        font-size: 2.75rem;
        opacity: .15;
        display: flex;
        gap: .25rem;
    }

    .breakdown-wrapper {
        border: none;
        border-radius: 18px;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .breakdown-card {
        border: none;
        border-radius: 14px;
        background: #f8f9fc;
        height: 100%;
    }

    [data-bs-theme="dark"] .breakdown-card {
        background: #232b36;
    }

    .breakdown-card-header {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .9rem 1rem;
        border-bottom: 1px solid #eef2f7;
    }

    [data-bs-theme="dark"] .breakdown-card-header {
        border-bottom-color: #36404a;
    }

    .breakdown-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .breakdown-card.card-office .breakdown-card-icon {
        background: rgba(102, 88, 221, .15);
        color: #6658dd;
    }

    .breakdown-card.card-wfh .breakdown-card-icon {
        background: rgba(10, 207, 151, .15);
        color: #0acf97;
    }

    .breakdown-card.card-leave .breakdown-card-icon {
        background: rgba(51, 169, 224, .15);
        color: #33a9e0;
    }

    .breakdown-card.card-nodata .breakdown-card-icon {
        background: rgba(152, 166, 173, .18);
        color: #6c757d;
    }

    .breakdown-card-header h6 {
        margin-bottom: 0;
        font-weight: 700;
        flex-grow: 1;
    }

    .count-badge {
        border-radius: 2rem;
        padding: .15rem .6rem;
        font-size: .75rem;
        font-weight: 700;
        color: #fff;
    }

    .card-office .count-badge {
        background: #6658dd;
    }

    .card-wfh .count-badge {
        background: #0acf97;
    }

    .card-leave .count-badge {
        background: #33a9e0;
    }

    .card-nodata .count-badge {
        background: #98a6ad;
    }

    .breakdown-table thead th {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #6c757d;
        font-weight: 700;
        border-bottom: 1px solid #eef2f7;
        padding: .5rem 1rem;
    }

    [data-bs-theme="dark"] .breakdown-table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .breakdown-table td {
        padding: .45rem 1rem;
        vertical-align: middle;
        font-size: .875rem;
    }

    .breakdown-empty {
        text-align: center;
        padding: 2rem 1rem;
        color: #98a6ad;
    }

    .breakdown-empty i {
        font-size: 2.5rem;
        opacity: .5;
    }

    .breakdown-view-all {
        text-align: center;
        padding: .75rem;
    }

    .updated-banner {
        background-color: rgba(102, 88, 221, .08);
        border-radius: 14px;
        padding: .85rem 1.1rem;
    }

    [data-bs-theme="dark"] .updated-banner {
        background-color: rgba(146, 152, 245, .12);
    }

    .updated-banner-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(102, 88, 221, .15);
        color: #6658dd;
        flex-shrink: 0;
    }

    [data-bs-theme="dark"] .updated-banner-icon {
        background: rgba(146, 152, 245, .2);
        color: #9298f5;
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
                Employee Status
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card status-tile tile-office">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="status-tile-icon"><i class="mdi mdi-office-building-outline"></i></div>
                <div>
                    <h2><span data-plugin="counterup"><?php echo count($inofficetoday); ?></span></h2>
                    <p class="fw-bold mb-0">In Office</p>
                    <p class="text-muted mb-0 font-13">Employees</p>
                </div>
                <div class="status-tile-illustration"><i class="mdi mdi-office-building"></i></div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card status-tile tile-wfh">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="status-tile-icon"><i class="mdi mdi-home-outline"></i></div>
                <div>
                    <h2><span data-plugin="counterup"><?php echo count($workfromhome); ?></span></h2>
                    <p class="fw-bold mb-0">Work From Home</p>
                    <p class="text-muted mb-0 font-13">Employees</p>
                </div>
                <div class="status-tile-illustration"><i class="mdi mdi-home-city"></i></div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card status-tile tile-leave">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="status-tile-icon"><i class="mdi mdi-palm-tree"></i></div>
                <div>
                    <h2><span data-plugin="counterup"><?php echo count($leave); ?></span></h2>
                    <p class="fw-bold mb-0">On Leave</p>
                    <p class="text-muted mb-0 font-13">Employees</p>
                </div>
                <div class="status-tile-illustration"><i class="mdi mdi-beach"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card breakdown-wrapper">
            <div class="card-body">
                <h5 class="fw-bold mb-0">Detailed Breakdown</h5>
                <p class="text-muted font-13 mb-3">View employees by location status</p>

                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="breakdown-card card-office">
                            <div class="breakdown-card-header">
                                <div class="breakdown-card-icon"><i class="mdi mdi-office-building-outline"></i></div>
                                <h6>Employees In Office</h6>
                                <span class="count-badge"><?php echo count($inofficetoday); ?></span>
                            </div>
                            <?php if (empty($inofficetoday)) : ?>
                                <div class="breakdown-empty">
                                    <div><i class="mdi mdi-tray-outline"></i></div>
                                    <p class="mb-0 mt-2">No employees in office</p>
                                </div>
                            <?php else : ?>
                                <table class="table breakdown-table mb-0">
                                    <thead>
                                        <tr>
                                            <th width="15%">#</th>
                                            <th>Employee Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j = 0;
                                        foreach ($inofficetoday as $inoffice) {
                                            $j++; ?>
                                            <tr class="<?php echo ($j > $visible_row_limit) ? 'd-none breakdown-extra-row' : ''; ?>">
                                                <td><?php echo $j; ?></td>
                                                <td>
                                                    <?php
                                                    echo $inoffice['name'] . ' ' . $inoffice['last_name'];
                                                    if ($inoffice['numday'] == '.5') {
                                                        echo ' (Half Day)';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php if (count($inofficetoday) > $visible_row_limit) : ?>
                                    <div class="breakdown-view-all">
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill view-all-btn"><i class="mdi mdi-eye-outline me-1"></i>View All (<?php echo count($inofficetoday); ?>)</button>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="breakdown-card card-wfh">
                            <div class="breakdown-card-header">
                                <div class="breakdown-card-icon"><i class="mdi mdi-home-outline"></i></div>
                                <h6>Work From Home</h6>
                                <span class="count-badge"><?php echo count($workfromhome); ?></span>
                            </div>
                            <?php if (empty($workfromhome)) : ?>
                                <div class="breakdown-empty">
                                    <div><i class="mdi mdi-tray-outline"></i></div>
                                    <p class="mb-0 mt-2">No employees working from home</p>
                                </div>
                            <?php else : ?>
                                <table class="table breakdown-table mb-0">
                                    <thead>
                                        <tr>
                                            <th width="15%">#</th>
                                            <th>Employee Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j = 0;
                                        foreach ($workfromhome as $wfh) {
                                            $j++; ?>
                                            <tr class="<?php echo ($j > $visible_row_limit) ? 'd-none breakdown-extra-row' : ''; ?>">
                                                <td><?php echo $j; ?></td>
                                                <td>
                                                    <?php
                                                    echo $wfh['name'] . ' ' . $wfh['last_name'];
                                                    if ($wfh['number_wfh'] == '.5') {
                                                        echo ' (Half Day)';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php if (count($workfromhome) > $visible_row_limit) : ?>
                                    <div class="breakdown-view-all">
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill view-all-btn"><i class="mdi mdi-eye-outline me-1"></i>View All (<?php echo count($workfromhome); ?>)</button>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="breakdown-card card-leave">
                            <div class="breakdown-card-header">
                                <div class="breakdown-card-icon"><i class="mdi mdi-palm-tree"></i></div>
                                <h6>On Leave</h6>
                                <span class="count-badge"><?php echo count($leave); ?></span>
                            </div>
                            <?php if (empty($leave)) : ?>
                                <div class="breakdown-empty">
                                    <div><i class="mdi mdi-tray-outline"></i></div>
                                    <p class="mb-0 mt-2">No employees on leave</p>
                                </div>
                            <?php else : ?>
                                <table class="table breakdown-table mb-0">
                                    <thead>
                                        <tr>
                                            <th width="15%">#</th>
                                            <th>Employee Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j = 0;
                                        foreach ($leave as $lv) {
                                            $j++; ?>
                                            <tr class="<?php echo ($j > $visible_row_limit) ? 'd-none breakdown-extra-row' : ''; ?>">
                                                <td><?php echo $j; ?></td>
                                                <td>
                                                    <?php
                                                    echo $lv['name'] . ' ' . $lv['last_name'];
                                                    if ($lv['leavex'] == '-0.5') {
                                                        echo ' (Half Day)';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php if (count($leave) > $visible_row_limit) : ?>
                                    <div class="breakdown-view-all">
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill view-all-btn"><i class="mdi mdi-eye-outline me-1"></i>View All (<?php echo count($leave); ?>)</button>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="breakdown-card card-nodata">
                            <div class="breakdown-card-header">
                                <div class="breakdown-card-icon"><i class="mdi mdi-information-outline"></i></div>
                                <h6>No Data</h6>
                                <span class="count-badge"><?php echo count($nodata_employees); ?></span>
                            </div>
                            <?php if (empty($nodata_employees)) : ?>
                                <div class="breakdown-empty">
                                    <div><i class="mdi mdi-tray-outline"></i></div>
                                    <p class="mb-0 mt-2">No unmarked employees</p>
                                </div>
                            <?php else : ?>
                                <table class="table breakdown-table mb-0">
                                    <thead>
                                        <tr>
                                            <th width="15%">#</th>
                                            <th>Employee Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j = 0;
                                        foreach ($nodata_employees as $missing) {
                                            $j++; ?>
                                            <tr class="<?php echo ($j > $visible_row_limit) ? 'd-none breakdown-extra-row' : ''; ?>">
                                                <td><?php echo $j; ?></td>
                                                <td><?php echo $missing['name'] . ' ' . $missing['last_name']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php if (count($nodata_employees) > $visible_row_limit) : ?>
                                    <div class="breakdown-view-all">
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill view-all-btn"><i class="mdi mdi-eye-outline me-1"></i>View All (<?php echo count($nodata_employees); ?>)</button>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3 mb-3">
    <div class="col-12">
        <div class="updated-banner d-flex align-items-center gap-2">
            <div class="updated-banner-icon"><i class="mdi mdi-information-outline"></i></div>
            <div class="font-13">Data is updated in real-time. Last updated on <strong><?php echo date('M d, Y h:i A'); ?></strong></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.view-all-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var card = btn.closest('.breakdown-card');
                card.querySelectorAll('.breakdown-extra-row').forEach(function(row) {
                    row.classList.remove('d-none');
                });
                btn.closest('.breakdown-view-all').remove();
            });
        });
    });
</script>
