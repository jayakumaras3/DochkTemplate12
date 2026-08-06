<style>
    .holiday-info-banner {
        background-color: rgba(102, 88, 221, .08);
        border: 1px solid rgba(102, 88, 221, .18);
        border-radius: 14px;
        padding: .85rem 1.1rem;
    }

    [data-bs-theme="dark"] .holiday-info-banner {
        background-color: rgba(146, 152, 245, .12);
        border-color: rgba(146, 152, 245, .25);
    }

    .holiday-info-banner .mdi {
        color: #6658dd;
    }

    [data-bs-theme="dark"] .holiday-info-banner .mdi {
        color: #9298f5;
    }

    .holiday-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
        height: 100%;
    }

    .holiday-card-header {
        display: flex;
        align-items: center;
        gap: .65rem;
        margin-bottom: 1rem;
    }

    .holiday-flag-badge {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        background: #f1f3fa;
    }

    [data-bs-theme="dark"] .holiday-flag-badge {
        background: #232b36;
    }

    .holiday-card-header h4 {
        margin-bottom: 0;
        flex-grow: 1;
    }

    .holiday-count-badge {
        border-radius: 2rem;
        padding: .3rem .8rem;
        font-size: .8rem;
        font-weight: 700;
    }

    .holiday-count-badge.badge-india {
        background: rgba(10, 207, 151, .15);
        color: #0acf97;
    }

    .holiday-count-badge.badge-us {
        background: rgba(51, 169, 224, .15);
        color: #33a9e0;
    }

    .holiday-table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .holiday-table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .holiday-table td {
        vertical-align: middle;
    }

    .date-pill {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        border-radius: .5rem;
        padding: .3rem .6rem;
        font-size: .8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .date-pill.pill-india {
        background: rgba(10, 207, 151, .12);
        color: #0acf97;
    }

    .date-pill.pill-us {
        background: rgba(51, 169, 224, .12);
        color: #33a9e0;
    }

    .day-pill {
        display: inline-block;
        border-radius: .5rem;
        padding: .3rem .6rem;
        font-size: .8rem;
        font-weight: 600;
        background: #eef2f7;
        color: #6c757d;
    }

    [data-bs-theme="dark"] .day-pill {
        background: #36404a;
        color: #cedeef;
    }

    .restricted-tag {
        color: #fa5c7c;
        font-style: italic;
        font-size: .75rem;
        font-weight: 600;
        margin-left: .4rem;
    }

    .holiday-showing-note {
        color: #98a6ad;
        font-size: .8rem;
        padding-top: .75rem;
    }

    .holiday-legend {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        background: #fff;
        border: 1px solid #eef2f7;
        border-radius: 2rem;
        padding: .6rem 1.5rem;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.08);
    }

    [data-bs-theme="dark"] .holiday-legend {
        background: #232b36;
        border-color: #36404a;
    }

    .holiday-legend .legend-chip {
        display: flex;
        align-items: center;
        gap: .4rem;
        font-size: .8rem;
        font-weight: 600;
        color: #6c757d;
    }

    [data-bs-theme="dark"] .holiday-legend .legend-chip {
        color: #cedeef;
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .legend-dot.dot-company {
        background: #0acf97;
    }

    .legend-dot.dot-restricted {
        background: #fa5c7c;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <form class="form-horizontal" action="<?php echo base_url('etrack/dashboard/holiday_cal'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-select" name='show_year' id='show_year'>
                                <?php
                                $endyear = date('Y');
                                for ($i = $endyear; $i >= 2025; $i--) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mt-1">
                            <input type="submit" class="btn btn-outline-info rounded-pill btn-xs waves-effect waves-light" value="Show Holidays">
                        </div>
                    </div>
                </form>

            </div>
            <h4 class="page-title">
                Holidays - <?php echo $show_year; ?>
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="holiday-info-banner d-flex align-items-center gap-2 mb-3">
            <i class="mdi mdi-information-outline font-18"></i>
            <span class="font-13">Restricted holidays are organization specific and may not apply to all employees.</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card holiday-card">
            <div class="card-body">
                <div class="holiday-card-header">
                                        <span class="holiday-flag-badge"><img width="20" src="<?php echo base_url('public/creative/assets/images/flags/india.jpg'); ?>" alt="India Flag"></span>
                    <h4>India Holidays</h4>
                    <span class="holiday-count-badge badge-india"><?php echo count($indiaholidaydata); ?> Holidays</span>
                </div>
                <div class="table-responsive">
                    <table class="table holiday-table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Holiday</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($indiaholidaydata as $ih) { ?>
                                <tr>
                                    <td>
                                        <span class="date-pill pill-india"><i class="mdi mdi-calendar-blank-outline"></i> <?php echo date("M d, Y", strtotime($ih['holiday_dt'])); ?></span>
                                    </td>
                                    <td><span class="day-pill"><?php echo ucwords(date('D', strtotime($ih['holiday_dt']))); ?></span></td>
                                    <td>
                                        <?php echo $ih['description']; ?>
                                        <?php if ($ih['type'] == 2) { ?>
                                            <span class="restricted-tag">Restricted</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="holiday-showing-note">Showing 1 to <?php echo count($indiaholidaydata); ?> of <?php echo count($indiaholidaydata); ?> holidays</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card holiday-card">
            <div class="card-body">
                <div class="holiday-card-header">
                    <span class="holiday-flag-badge"><img width="20" src="<?php echo base_url('public/creative/assets/images/flags/us.jpg'); ?>" alt="US Flag"></span>
                    <h4>US Holidays</h4>
                    <span class="holiday-count-badge badge-us"><?php echo count($usholidaydata); ?> Holidays</span>
                </div>
                <div class="table-responsive">
                    <table class="table holiday-table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Holiday</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usholidaydata as $ih) { ?>
                                <tr>
                                    <td>
                                        <span class="date-pill pill-us"><i class="mdi mdi-calendar-blank-outline"></i> <?php echo date("M d, Y", strtotime($ih['holiday_dt'])); ?></span>
                                    </td>
                                    <td><span class="day-pill"><?php echo ucwords(date('D', strtotime($ih['holiday_dt']))); ?></span></td>
                                    <td><?php echo $ih['description']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="holiday-showing-note">Showing 1 to <?php echo count($usholidaydata); ?> of <?php echo count($usholidaydata); ?> holidays</div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-center">
        <div class="holiday-legend">
            <span class="legend-chip"><span class="legend-dot dot-company"></span> Company-wide Holiday</span>
            <span class="legend-chip"><span class="legend-dot dot-restricted"></span> Restricted Holiday</span>
        </div>
    </div>
</div>
