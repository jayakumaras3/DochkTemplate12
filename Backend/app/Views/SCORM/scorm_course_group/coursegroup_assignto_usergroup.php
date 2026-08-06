<?php if (session()->get('error')) :
    echo '<script>alert("' . session()->get('error') . '")</script>';
endif;
$client =  session()->get('client');
$arraystakeholders  = explode(',', $client);
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/Scorm_learn_group') ?>"><?= lang('UI_Text.Course_Groups') ?></a></li>

                </ol> 
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Assign_Course_Group_to_User_Group') ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <?php
                // The course group itself isn't picked here - it's whichever group the
                // user arrived from ($sc_cgid) - so name it explicitly, otherwise the
                // page only shows a "user group" dropdown with no visible course group,
                // which reads as if the form's own title/direction is wrong.
                $currentCourseGroup = null;
                foreach ($course_group as $group) {
                    if ($group['sc_cgid'] == $sc_cgid) {
                        $currentCourseGroup = $group['description'];
                        break;
                    }
                }
                ?>
                <?php if ($currentCourseGroup !== null): ?>
                    <p class="mb-2"><strong><?= lang('UI_Text.Course_Groups') ?>:</strong> <?= esc($currentCourseGroup) ?></p>
                <?php endif; ?>
                <form action="<?php echo base_url('SCORM/scorm_user_group/assignCoursegrouptoUsergroup') ?>" method="post" autocomplete="off" id="submitForm"><?= csrf_field() ?>
                    <div class="mb-2">
                        <label><?= lang('UI_Text.Select_User_Group') ?></label>
                        <select class="form-select" name="u_gid" required>

                            <?php foreach ($user_group as $group) { ?>
                                <option value="<?php echo $group['sc_cgid'] ?>"><?php echo $group['description'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="hidden" name="c_gid" value="<?php echo $sc_cgid ?>" />
                        <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light" id="submitButton"><?= lang('UI_Text.Assign_Course_Group_to_User_Group') ?></button>
                    </div>
                    <?php if (isset($validation)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= lang('UI_Text.Users') ?></th>
                            <th><?= lang('UI_Text.Delete') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($group_users  as $users) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo  $users['name'] . ' ' . $users['last_name'];  ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('SCORM/Scorm_learn_group/del_coursegroup_user') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="scgu_id" value="<?php echo $users['scgu_id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light " onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span><?= lang('UI_Text.Delete') ?></button>
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
</div>