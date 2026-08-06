<style>
    .border-table {
        display: table;
        width: 100%;
        border-collapse: collapse;
    }

    .border-row {
        display: table-row;
    }

    .border-cell {
        display: table-cell;
        border: 1px solid #dee2e6;
        padding: 8px;
        vertical-align: middle;
    }

    .header-cell {
        font-weight: bold;
        background-color: #f8f9fa;
    }

    textarea.form-control {
        resize: vertical;
    }

    select.form-control,
    textarea.form-control {
        width: 100%;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url() . "Etrack/exit_clearance" ?>">
                            Exit Clearance</a></li>
                </ol>
            </div>
            <h4 class="page-title">Exit Clearance Form</h4>
        </div>
    </div>
</div>
<?php $canEdit = in_array(session()->get('id_user'), ['1103', '1115', '1202', '1115', '1', $manager, '834']); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <?php foreach ($getExitclearanceheader as $header): ?>
                    <div class="border-table mt-4">

                        <!-- 🔁 FORM 1: Main Header "Change Status" Only -->
                        <form action="<?= base_url('Etrack/exit_clearance/updateHeaderStatus') ?>" method="POST"><?= csrf_field() ?>
                            <div class="border-row">
                                <div class="border-cell" style="width:40%;">
                                    <h4><?= esc($header['description']) ?></h4>
                                </div>
                                <div class="border-cell" style="width:20%;">
                                    <?php
                                    $header_status = '';
                                    foreach ($getUserexitformstatus as $row) {
                                        if ($row['fk_header_id'] == $header['header_id']) {
                                            $header_status = $row['status'];
                                            break;
                                        }
                                    }
                                    switch ($header_status) {
                                        case 3:
                                            echo 'Initiate';
                                            break;
                                        case 2:
                                            echo 'Approved';
                                            break;
                                        case 1:
                                            echo 'Rejected';
                                            break;
                                        case 0:
                                            echo 'Delete';
                                            break;
                                        default:
                                            echo '';
                                            break;
                                    }
                                    ?>
                                </div>
                                <?php if ($canEdit): ?>
                                    <div class="border-cell" style="width:20%;">
                                        <select name="status" class="form-control" required>
                                            <option value="">-- Select --</option>
                                            <option value="3" <?= $header_status == 3 ? 'selected' : '' ?>>Initiate</option>
                                            <option value="2" <?= $header_status == 2 ? 'selected' : '' ?>>Approved</option>
                                            <option value="1" <?= $header_status == 1 ? 'selected' : '' ?>>Rejected</option>
                                            <option value="0" <?= $header_status == 0 ? 'selected' : '' ?>>Delete</option>
                                        </select>
                                    </div>
                                    <div class="border-cell" style="width:20%;">
                                        <input type="hidden" name="user_id" value="<?= $user_id ?>">
                                        <input type="hidden" name="fk_header_id" value="<?= $header['header_id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">Change Status</button>
                                    </div>
                                <?php else: ?>
                                    <div class="border-cell" style="width:20%;"></div>
                                    <div class="border-cell" style="width:20%;"></div>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <!-- 🧾 Subheaders Table -->
                    <!-- 📝 FORM 2: Subheaders Save All -->
                    <form action="<?= base_url('Etrack/exit_clearance/updateSubheaders') ?>" method="POST"><?= csrf_field() ?>
                        <div class="border-table">

                            <div class="border-row header-row">
                                <div class="border-cell header-cell">#</div>
                                <div class="border-cell header-cell">Description</div>
                                <div class="border-cell header-cell">Status</div>
                                <div class="border-cell header-cell">Change</div>

                            </div>

                            <?php
                            $j = 0;
                            foreach ($getExitclearanceSubheader as $subheader):
                                if ($subheader['sub_main_header'] !== $header['header_id'])
                                    continue;
                                $j++;

                                $sub_status = '';
                                $comment = '';
                                foreach ($getUserexitformstatus as $row) {
                                    if ($row['fk_header_id'] == $subheader['header_id']) {
                                        $sub_status = $row['status'];
                                        $comment = $row['comment'];
                                        break;
                                    }
                                }
                                ?>
                                <div class="border-row">
                                    <div class="border-cell"><?= $j ?></div>
                                    <div class="border-cell"><?= esc($subheader['description']) ?></div>
                                    <div class="border-cell">
                                        <?php
                                        if ($subheader['description'] !== 'Comment') {
                                            switch ($sub_status) {
                                                case 3:
                                                    echo 'Done';
                                                    break;
                                                case 2:
                                                    echo 'NA';
                                                    break;
                                                case 1:
                                                    echo 'Yes';
                                                    break;
                                                case 0:
                                                    echo 'No';
                                                    break;
                                                default:
                                                    echo '';
                                                    break;
                                            }

                                        } else {
                                            // echo esc($comment);
                                        }
                                        ?>
                                    </div>
                                    <?php if ($canEdit): ?>
                                        <div class="border-cell">
                                            <?php if ($subheader['description'] === 'Comment'): ?>
                                                <textarea name="comment[<?= $subheader['header_id'] ?>]"
                                                    class="form-control"><?= esc($comment) ?></textarea>
                                            <?php else: ?>
                                                <select name="status[<?= $subheader['header_id'] ?>]" class="form-control">
                                                    <option value="">-- Select --</option>
                                                    <option value="3" <?= $sub_status == 3 ? 'selected' : '' ?>>Done</option>
                                                    <option value="2" <?= $sub_status == 2 ? 'selected' : '' ?>>NA</option>
                                                    <option value="1" <?= $sub_status == 1 ? 'selected' : '' ?>>Yes</option>
                                                    <option value="0" <?= $sub_status === '0' || $sub_status === 0 ? 'selected' : '' ?>>
                                                        No
                                                    </option>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="border-cell"></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            <input type="hidden" name="fk_header_id" value="<?= $header['header_id'] ?>">

                            <!-- Save Button for Subheaders -->
                            <?php if ($canEdit): ?>
                                <div class="border-row mt-2">
                                    <div class="border-cell"></div>
                                    <div class="border-cell"></div>
                                    <div class="border-cell"></div>
                                    <div class="border-cell" style="width:10%;">
                                        <button type="submit" class="btn btn-primary btn-sm w-100">Save</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>


                <?php endforeach; ?>

            </div>
        </div>
    </div>