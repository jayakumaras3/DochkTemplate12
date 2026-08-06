<?php if (session()->get('error')):
    echo '<script>alert("' . session()->get('error') . '")</script>';
endif;
$client = session()->get('client');
$arraystakeholders = explode(',', $client);
?>
<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .user-group-add-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .user-group-add-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .user-group-add-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .user-group-add-card table tbody td {
        vertical-align: middle;
    }

    .user-group-add-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .user-group-add-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .user-group-add-card .dataTables_length select,
    [data-bs-theme="dark"] .user-group-add-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .user-group-add-card .pagination .page-link {
        border-radius: 0;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/Scorm_user_group'); ?>"><?=  lang('UI_Text.User_Groups'); ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?=  lang('Buttons.Assign_Users'); ?></h4>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-4">
        <div class="card user-group-add-card">
            <div class="card-body">
                <h6><?=  lang('UI_Text.Select_Users'); ?></h6>
                <form class="form-horizontal1" id="addusersForm" id="submitForm"><?= csrf_field() ?>
                    <div class="mb-3">
                        <select class="form-select select2-multiple" data-toggle="select2" data-width="100%"
                            multiple="multiple" name="user_id[]" required="" >
                            <?php foreach ($all_users as $users) {
                                $key = array_search($users['id_user'], array_column($assigned_courses, 'id_user'));
                                if (!empty($key) || $key === 0) {
                                } else {
                                    echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="hidden" name="sc_cgid" value="<?php echo $row[0]['sc_cgid'] ?>">
                        <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect btn-xs waves-light submitButton">
                           <?=  lang('Buttons.Add'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="col-lg-8">
        <div class="card user-group-add-card">
            <div class="card-body">
                <table id="user-group-add-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th><?=  lang('UI_Text.User_Name'); ?></th>
                            <th><?=  lang('UI_Text.Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($assigned_courses as $assigned) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $assigned['username'] ?></td>

                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($form_link_1) ?>"
                                        method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="sc_cgid" value="<?php echo $row[0]['sc_cgid'] ?>">
                                        <input type="hidden" name="assign_id" value="<?php echo $assigned['assign_id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light"
                                            onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><?=  lang('Buttons.Delete'); ?></button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $('#addusersForm').on('submit', function (event) {

        event.preventDefault();

        var dataString = new FormData($('#addusersForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url($form_link) ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function (data) {

                    var obj = JSON.parse(data);

                    console.log(obj);

                    if (obj.status === 'OK') {
                        console.log('inside on condition');
                        //window.location.href = 'project_settings.php';
                        location.reload();


                    } else {

                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                    }

                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            })
        } else {
            message("Your Browser Don't support FormData API! Use IE 10 or Above!");
        }

    });

    $(document).ready(function () {
        $('#user-group-add-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, 2]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Users'), 'js') ?>',
                lengthMenu: '_MENU_',
                info: '<?= esc(lang('UI_Text.Datatable_Info'), 'js') ?>',
                infoEmpty: '<?= esc(lang('UI_Text.Datatable_Info_Empty'), 'js') ?>',
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            }
        });
    });
</script>
<script>
    // Use event delegation or loop through all forms
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var button = form.querySelector('.submitButton'); // Use class instead of ID

            // Check if the button is already disabled (indicating the form is being submitted)
            if (button.disabled) {
                e.preventDefault(); // Prevent the form from being submitted again
                return false;
            }

            // Disable the submit button and change its text to 'Submitting...'
            button.disabled = true;
            button.innerHTML = 'Submitting...';
        });
    });
</script>
