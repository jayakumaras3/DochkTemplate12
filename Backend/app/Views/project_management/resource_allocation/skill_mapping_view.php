<?php
$skills = array();
foreach ($department_list as $data) {
    $skills[$data['value']] = $data['name'];
}
?>
<style>
    .td {
        text-align: right;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_projects/resource_allocation'); ?>">Resource Allocation</a></li>
                </ol>
            </div>
            <h4 class="page-title">Skill Mapping</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_projects/assign_skill_employee') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">User</label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select " name="user_select" required="">
                                <?php foreach ($all_users as $users) {
                                    echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Domain</label>
                        <div class="col-8 col-xl-9">
                            <select name="skill_val" class="form-control">
                                <?php
                                foreach ($skills as $x => $y) {
                                    echo '<option value="' . $x . '">' . $y . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Allocation</label>
                        <div class="col-8 col-xl-9">
                            <select name="allocation" class="form-control">
                                <option value="100">100%</option>
                                <option value="75">75%</option>
                                <option value="50">50%</option>
                                <option value="25">25%</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Proficiency</label>
                        <div class="col-8 col-xl-9">
                            <select name="proficiency" class="form-control">
                                <option value="1">Beginner/Novice</option>
                                <option value="2">Intermediate</option>
                                <option value="3">Advanced</option>
                                <option value="4">Expert/Mastery</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">

                        <div class="col-12 ">
                            <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                Assign
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Employee</td>
                            <td>Skill</td>
                            <td>Allocation</td>
                            <td>Proficiency</td>
                            <td>Delete</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($skill_assigned as $data) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $data['fname'] . ' ' . $data['lname']; ?></td>
                                <td><?php echo $data['skill_name']; ?></td>
                                <td><?php echo $data['allocation']; ?> %</td>
                                <td><?php switch ($data['proficiency']) {
                                        case 1:
                                            echo 'Beginner/Novice';
                                            break;
                                        case 2:
                                            echo 'Intermediate';
                                            break;
                                        case 3:
                                            echo 'Advanced';
                                            break;
                                        case 4:
                                            echo 'Expert/Mastery';
                                            break;
                                        default:
                                            echo 'Unknown';
                                            break;
                                    }; ?>


                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_projects/delete_skill'); ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="skill_map_id" value="<?php echo $data['skill_map_id']; ?>">
                                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>