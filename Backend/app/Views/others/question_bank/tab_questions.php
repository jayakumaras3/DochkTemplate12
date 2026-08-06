<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <!-- <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Ojts_consolidated'); ?>">OJT
                            Edit</a></li>

                </ol> -->
            </div>
            <h4 class="page-title">Question Bank Dashboard</h4>
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <form method="post" action="<?= base_url('Open/Question_bank/tab_questions_post') ?>" class="mb-0">
            <?php foreach ($tabs as $t): ?>
                <button type="submit" name="tabid" value="<?= esc($t['tabid']) ?>"
                    class="btn btn-outline-primary btn-xs waves-effect waves-light me-1"
                    style="<?= $t['tabid'] == $active_tab ? 'background-color:#094956;color:#fff;' : '' ?>">
                    <?= esc($t['tabid']) ?>
                </button>
            <?php endforeach; ?>
        </form>

        <a href="<?= base_url('Open/Question_bank/export_excel/' . $active_tab) ?>"
            class="btn btn-outline-success btn-xs waves-effect waves-light me-1">
            <i class="mdi mdi-file-excel"></i> Export Current Tab
        </a>
        <a href="<?= base_url('Open/Question_bank/export_excel_all') ?>" class="btn btn-outline-dark btn-xs waves-effect waves-light me-1">
            <i class="mdi mdi-file-excel"></i> Export All Tabs
        </a>

    </div>
</div>


<style>
    .highlight {
        background-color: #28a745 !important;
        color: green;
        font-weight: bold;
        */
    }
</style>
<div class="row" style="margin-top: 10px;">

    <div class="card">
        <div class="card-body">
            <table class="table dt-responsive w-100 dataTable no-footer dtr-inline">

                <thead>
                    <tr>
                        <th>#</th>
                        <th width="12%">Question</th>
                        <th width="12%">New Question</th>
                        <th width="12%">Option A</th>
                        <th width="12%">Option B</th>
                        <th width="12%">Option C</th>
                        <th width="12%">Option D</th>
                        <th width="12%">Remarks</th>
                        <th>Status</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $q): ?>
                        <tr>
                            <td><?= esc($q['qno']) - 1 ?></td>
                            <td><?= esc($q['question']); ?></td>
                            <td><?= esc($q['new_question']) ?></td>
                            <td class="<?= strtoupper($q['correct_answer']) == 'A' ? 'highlight' : '' ?>">
                                <?= esc($q['answer_a']) ?>
                            </td>
                            <td class="<?= strtoupper($q['correct_answer']) == 'B' ? 'highlight' : '' ?>">
                                <?= esc($q['answer_b']) ?>
                            </td>
                            <td class="<?= strtoupper($q['correct_answer']) == 'C' ? 'highlight' : '' ?>">
                                <?= esc($q['answer_c']) ?>
                            </td>
                            <td class="<?= strtoupper($q['correct_answer']) == 'D' ? 'highlight' : '' ?>">
                                <?= esc($q['answer_d']) ?>
                            </td>
                            <td><?= esc($q['remarks']) ?></td>
                            <td>
                                <?php $statuses = [
                                    '1' => ' - ',
                                    '2' => 'TSID',
                                    '3' => 'QA',
                                    '4' => 'Client',
                                    '5' => 'App',
                                    '6' => 'Rej'
                                ];
                                echo isset($statuses[$q['status']]) ? $statuses[$q['status']] : 'Unknown' ?>
                            </td>

                            <td>
                                <form method="post" action="<?= base_url('Open/Question_bank/edit_question') ?>">
                                    <input type="hidden" name="qb_id" value="<?= esc($q['qb_id']) ?>">
                                    <input type="hidden" name="tabid" value="<?= esc($active_tab) ?>">
                                    <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light">
                                        <span class="mdi mdi-pencil-outline"></span>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    </tbody>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>