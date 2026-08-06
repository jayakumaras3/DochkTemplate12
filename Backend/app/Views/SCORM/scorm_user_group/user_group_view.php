<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .user-group-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .user-group-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .user-group-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .user-group-card table tbody td {
        vertical-align: middle;
    }

    .user-group-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .user-group-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .user-group-card .dataTables_length select,
    [data-bs-theme="dark"] .user-group-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .user-group-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"><?= lang('UI_Text.User_Groups'); ?></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card user-group-card">
            <div class="card-body">
                <form action="<?php echo base_url($form_link) ?>" method="post" autocomplete="off" id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <label class="form-label"><?=  lang('UI_Text.User_Group_Name'); ?></label>
                            <input type="text" name="description" class="form-control" value="" required="" />
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect btn-xs waves-light" id="submitButton"><?= lang('Buttons.Create'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card user-group-card">
            <div class="card-body">
                <table id="user-group-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th width="10%">#</th>
                            <th><?=  lang('UI_Text.User_Group_Name'); ?></th>
                            <th width="15%"><?=  lang('UI_Text.Action'); ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($coursegroupdata  as $eachcoursegroupdata) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo  $eachcoursegroupdata['description'];  ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <form class="form-horizontal mb-0" action="<?php echo base_url($form_link_1) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="sc_cgid" value="<?php echo $eachcoursegroupdata['sc_cgid'] ?>">
                                            <button type="submit" class="btn btn-outline-primary rounded-pill btn-xs waves-effect waves-light"><?=  lang('Buttons.Assign_Users'); ?></button>
                                        </form>
                                        <form class="form-horizontal mb-0" action="<?php echo base_url($form_link_2) ?>" method="POST" onsubmit="return confirm('<?= lang('Alert.Aler_002') ?>')"><?= csrf_field() ?>
                                            <input type="hidden" name="sc_cgid" value="<?php echo $eachcoursegroupdata['sc_cgid'] ?>">
                                            <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light"><?=  lang('Buttons.Delete'); ?></button>
                                        </form>
                                    </div>
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
    function target_popup1(url) {

        url = url.trim()
        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        newwin = window.open('http://' + url, 'windowname4', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }

    function target_popup2(filename, demoid) {

        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        newwin = window.open('http://purpleframetech.us/demos/upload/client/' + demoid + '/' + filename, 'windowname5', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }
    $(document).ready(function() {
        $('#user-group-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, 2]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Groups'), 'js') ?>',
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
