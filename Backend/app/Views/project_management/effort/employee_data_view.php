<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/Effort_Tracker/PM_Project_Effort'); ?>">Project Effort</a></li>
                </ol>
            </div>
            <h4 class="page-title">Employee Data View | <?php if (isset($user_information) && is_array($user_information)) {
                                                            echo $user_information[0]['name'] . ' ' . $user_information[0]['last_name'];
                                                        } ?></h4>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

                <table class="table table-bordered table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Work Week</th>
                            <th>Effort</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_effort = 0;
                        if (!empty($user_data) && is_array($user_data)) { ?>
                            <?php foreach ($user_data as $data) { ?>
                                <tr>
                                    <td><?php $weekStr = $data['work_week'];
                                        $date = new DateTime($weekStr);
                                        $startDate = $date->format('Y-m-d'); // First day of the week
                                        $date->modify('+6 days');
                                        $endDate = $date->format('Y-m-d');   // Last day of the week
                                        echo $data['work_week'];
                                        echo " | $startDate to $endDate";
                                        ?></td>
                                    <td style="text-align: right;"><?php
                                                                    $decimalTime = $data['effort'];
                                                                    $total_effort += $data['effort'];
                                                                    $hours = floor($decimalTime);
                                                                    $minutes = ($decimalTime - $hours) * 60;
                                                                    $formattedTime = sprintf("%d:%02d", $hours, $minutes);
                                                                    echo $formattedTime; // Outputs: 4:30
                                                                    ?></td>
                                    <td><?php echo $data['description']; ?></td>
                                    <td>
                                        <?php if ($data['work_week'] == date('Y-\WW') || $data['work_week'] == date('Y-\WW', strtotime('-1 week'))) { ?>
                                            <form method="post" class="reject-pm-form" action="<?php echo base_url('Project_Manage/Effort_Tracker/Reject_effort_by_pm'); ?>"><?= csrf_field() ?>
                                                <input type="hidden" name="pe_id" value="<?php echo $data['pe_id']; ?>">
                                                <input type="text" name="comment" class="form-control form-control-sm mb-1" placeholder="Comment (optional)" maxlength="50">
                                                <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light">Reject by PM</button>
                                            </form>
                                        <?php } else { ?>
                                            <span class="text-muted">N/A</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td><strong>Total Effort</strong></td>
                                <td style="text-align: right;"><strong><?php // echo $total_effort;
                                                                        $hours = floor($total_effort);
                                                                        $minutes = ($total_effort - $hours) * 60;
                                                                        $formattedTime = sprintf("%d:%02d", $hours, $minutes);
                                                                        echo $formattedTime;
                                                                        ?></strong></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<script>
    var effortCsrfName = '<?= csrf_token() ?>';
    var effortCsrfHash = '<?= csrf_hash() ?>';

    $(document).on('submit', '.reject-pm-form', function(event) {
        event.preventDefault();

        var form = $(this);
        var row = form.closest('tr');
        var buttons = row.find('button[type="submit"]');

        buttons.prop('disabled', true);

        // The token embedded at page load can go stale (e.g. if it was already
        // used by another form on this page) before this form is submitted, so
        // always send the latest known hash rather than the static one.
        form.find('input[name="' + effortCsrfName + '"]').val(effortCsrfHash);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.csrfHash) {
                    effortCsrfHash = response.csrfHash;
                    $('.reject-pm-form input[name="' + effortCsrfName + '"]').val(effortCsrfHash);
                }

                if (response.status === 'OK') {
                    row.fadeOut(200, function() {
                        $(this).remove();
                    });
                } else {
                    alert(response.message || 'Something went wrong. Please contact Site Admin!');
                    buttons.prop('disabled', false);
                }
            },
            error: function(xhr) {
                if (xhr.status === 403) {
                    alert('Your session security token has expired. The page will now reload.');
                    window.location.reload();
                    return;
                }
                alert('Request failed. Please try again.');
                buttons.prop('disabled', false);
            }
        });
    });
</script>