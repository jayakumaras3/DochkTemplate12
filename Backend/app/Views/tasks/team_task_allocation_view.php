<?php
$skills = array();
foreach ($department_list as $data) {
    $skills[$data['value']] = $data['name'];
}
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Manager Task Allocation</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="bulkEffortForm" action="<?= base_url('Task/Task_manage/allocate_effort_bulk') ?>" method="POST"><?= csrf_field() ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>UCN</th>
                                <!-- <th>Project</th> -->
                                <th>Skill</th>
                                <th>Stage</th>
                                <th>Effort</th>
                                <th>Remarks</th>
                                <th>Employee<span class="text-danger">*</span></th>
                                <th>Effort<span class="text-danger">*</span></th>
                                <!-- <th>Allocate</th> -->
                                <th>Allocated</th>
                                <th>Actual</th>
                                <th width=5%>View</th>
                                <th width=5%>Close</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grouped_tasks = [];
                            foreach ($active_tasks as $task) {
                                $grouped_tasks[$task['projectname']][] = $task;
                            }

                            foreach ($grouped_tasks as $projectName => $tasks):
                                $collapseId = 'collapse_' . md5($projectName); // unique ID
                            ?>
                                <!-- Group Header Row -->
                                <tr style="background-color: #e9ecef; font-weight: bold;">
                                    <td colspan="11">
                                        <a href="javascript:void(0)" onclick="toggleRows('<?= $collapseId ?>', this)">
                                            <span class="arrow-icon"></span> Project Name - <?= $projectName ?>
                                        </a>

                                    </td>
                                </tr>
                                <?php foreach ($tasks as $task): ?>
                                    <tr class="<?= $collapseId ?>" id="<?= $collapseId ?>">
                                        <td width=5%><?= $task['ucn_id'] ?></td>
                                        <!-- <td><?= $task['projectname'] ?></td> -->
                                        <td width=10%><?= $skills[$task['skill_id']] ?></td>
                                        <td>
                                            <?php
                                            switch ($task['stage']) {
                                                case 1:
                                                    echo 'Alpha';
                                                    break;
                                                case 2:
                                                    echo 'Beta';
                                                    break;
                                                case 5:
                                                    echo 'Gamma';
                                                    break;
                                                case 0:
                                                    echo 'Gen';
                                                    break;
                                            }
                                            ?>
                                        </td>
                                        <td width=5%><?= $task['effort'] ?></td>
                                        <td width=15% title="<?= htmlspecialchars($task['remarks']) ?>">
                                            <?= strlen($task['remarks']) > 30 ? htmlspecialchars(substr($task['remarks'], 0, 30)) . '...' : htmlspecialchars($task['remarks']) ?>
                                        </td>

                                        <td width=15%>
                                            <select class="form-select user-select" name="user[]" onchange="checkEffortEntries()">
                                                <option value="">-- Select User --</option>
                                                <?php foreach ($usertable as $users): ?>
                                                    <option value="<?= $users['id_user'] ?>"><?= $users['name'] . ' ' . $users['last_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td width=8%><input type="number" class="form-control effort-input" name="effort[]" min="1" onchange="checkEffortEntries()"></td>
                                        <td><?= $task['tleffort'] ?></td>
                                        <td style="display:none;">
                                            <input type="hidden" name="stage[]" value="<?= $task['stage'] ?>">
                                            <input type="hidden" name="project_id[]" value="<?= $task['project_id'] ?>">
                                            <input type="hidden" name="ucn_id[]" value="<?= $task['ucn_id'] ?>">
                                            <input type="hidden" name="skill_id[]" value="<?= $task['skill_id'] ?>">
                                            <input type="hidden" name="ucn_mst_ids[]" value="<?= $task['ucn_mst_id'] ?>">
                                        </td>
                                        <td><?= $task['empeff'] ?></td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="viewTaskDetails(<?= $task['ucn_mst_id'] ?>)">
                                                <span class="mdi mdi-eye-outline"></span>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="closeTask(<?= $task['ucn_mst_id'] ?>)">
                                                <span class="mdi mdi-window-close"></span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    <?php endforeach; ?>
                    </tbody>

                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        <span data-bs-toggle="tooltip" data-bs-placement="top">
                            <button type="submit" class="btn btn-outline-primary" id="submitButton" disabled>
                                Allocate Task
                            </button>
                        </span>
                    </div>

                </form>

                <script>
                    function viewTaskDetails(ucn_mst_id) {
                        postToUrl('<?= base_url('Task/Task_manage/view_task_details') ?>', {
                            ucn_mst_id: ucn_mst_id
                        });
                    }

                    function closeTask(ucn_mst_id) {
                        if (confirm('Are you sure !! Do you want to close this Task?')) {
                            postToUrl('<?= base_url('Task/Task_manage/close_mng_task') ?>', {
                                ucn_mst_id: ucn_mst_id
                            });
                        }
                    }

                    function postToUrl(url, params) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;

                        for (const key in params) {
                            if (params.hasOwnProperty(key)) {
                                const hiddenField = document.createElement('input');
                                hiddenField.type = 'hidden';
                                hiddenField.name = key;
                                hiddenField.value = params[key];
                                form.appendChild(hiddenField);
                            }
                        }

                        document.body.appendChild(form);
                        form.submit();
                    }
                </script>


            </div>
        </div>
    </div>
</div>
<script>
    function toggleRows(collapseId) {
        const rows = document.querySelectorAll('.' + collapseId);
        rows.forEach(row => {
            row.style.display = (row.style.display === 'none') ? 'table-row' : 'none';
        });
    }
</script>
<script>
    function checkEffortEntries() {
        const userSelects = document.querySelectorAll('.user-select');
        const effortInputs = document.querySelectorAll('.effort-input');
        let enableButton = false;

        userSelects.forEach((select, i) => {
            const selectedValue = select.value.trim();
            const effortValue = effortInputs[i].value.trim();

            if (selectedValue !== '' && effortValue !== '') {
                enableButton = true;
            }
        });

        document.getElementById('submitButton').disabled = !enableButton;
    }
</script>