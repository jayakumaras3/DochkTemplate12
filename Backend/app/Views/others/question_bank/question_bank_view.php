<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url('Open/Question_bank/tab_questions_post'); ?>">Question Bank
                            Dashboard</a></li>

                </ol>
            </div>
            <h4 class="page-title">
                Question <?= esc($currentQuestionNumber) . '/' . esc($currentTabTotal) ?>
                <small>(Tab: <?= esc($question['tabid']) ?>)</small>
            </h4>




        </div>
    </div>
</div>



<div class="row">

    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped  w-100">
                    <tbody>
                        <form method="post" action="<?= base_url('Open/Question_bank/next') ?>">
                            <tr>
                                <td style="width: 50px;">Q</td>
                                <td><input type="text" class="form-control" name="question"
                                        placeholder="" value="<?= esc($question['question']) ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>New Q</td>
                                <td><input type="text" class="form-control" name="new_question"
                                        placeholder="" value="<?= esc($question['new_question']) ?>">
                                </td>
                            </tr>
                            <?php if (strtoupper(esc($question['correct_answer'])) == 'A') { ?>
                                <tr class="table-success">
                                <?php } else { ?>
                                <tr>
                                <?php } ?>
                                <td>Opt A</td>
                                <td><input class="form-control" type="text" id="ansA" name="answer_a"
                                        placeholder="" value="<?= esc($question['answer_a']) ?>">
                                </td>
                                </tr>
                                <?php if (strtoupper(esc($question['correct_answer'])) == 'B') { ?>
                                    <tr class="table-success">
                                    <?php } else { ?>
                                    <tr>
                                    <?php } ?>
                                    <td>Opt B</td>
                                    <td><input class="form-control" type="text" id="ansB" name="answer_b"
                                            placeholder="" value="<?= esc($question['answer_b']) ?>">
                                    </td>
                                    </tr>
                                    <?php if (strtoupper(esc($question['correct_answer'])) == 'C') { ?>
                                        <tr class="table-success">
                                        <?php } else { ?>
                                        <tr>
                                        <?php } ?>
                                        <td>Opt C</td>
                                        <td><input class="form-control" type="text" id="ansC" name="answer_c"
                                                placeholder="" value="<?= esc($question['answer_c']) ?>">
                                        </td>
                                        </tr>
                                        <?php if (strtoupper(esc($question['correct_answer'])) == 'D') { ?>
                                            <tr class="table-success">
                                            <?php } else { ?>
                                            <tr>
                                            <?php } ?>
                                            <td>Opt D</td>
                                            <td><input class="form-control" type="text" id="ansD" name="answer_d"
                                                    placeholder="" value="<?= esc($question['answer_d']) ?>">
                                            </td>
                                            </tr>
                                            <?php if (strlen(esc($question['remarks'])) > 2) { ?>
                                                <tr class="table-danger">
                                                <?php } else { ?>
                                                <tr>
                                                <?php } ?>
                                                <td>Remarks</td>
                                                <td><input class="form-control" type="text" name="remarks"
                                                        placeholder="" value="<?= esc($question['remarks']) ?>">
                                                </td>
                                                </tr>

                                                <input class="form-control" type="hidden" name="reference" placeholder="Enter Reference"
                                                    value="<?= esc($question['reference']) ?>">
                                                <input class="form-control" type="hidden" name="acs_code" placeholder="Enter ACS Code"
                                                    value="<?= esc($question['acs_code']) ?>">
                                                <tr>
                                                    <td> </td>
                                                    <td> <button type="submit" name="action" value="save"
                                                            class="btn btn-success btn-sm">Save</button>
                                                    </td>
                                                </tr>
                    </tbody>


                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="card">

            <div class="card-body">
                <form method="post" action="<?= base_url('Open/Question_bank/next') ?>">
                    <input type="hidden" name="qb_id" value="<?= esc($question['qb_id']) ?>">
                    <input type="hidden" name="next_id" value="<?= esc($next_id) ?>">
                    <input type="hidden" name="prev_id" value="<?= esc($prev_id) ?>">
                    <button type="submit" name="action" value="prev" class="btn btn-sm btn-danger" <?= !$prev_id ? 'disabled' : '' ?>>←
                        Back</button>
                    <button type="submit" name="action" value="next" class="btn btn-sm btn-warning" <?= !$next_id ? 'disabled' : '' ?>>Next
                        →</button>
                </form>
            </div>


            <div class="card-body">
                <form method="post" action="<?= base_url('Open/Question_bank/update_status') ?>">
                    <input type="hidden" name="qb_id" value="<?= esc($question['qb_id']) ?>">
                    <?php $statuses = [

                        '3' => 'Ready 4 QA',
                        '4' => 'Client',
                        '6' => 'Rejected'
                    ];
                    foreach ($statuses as $statusId => $label):
                        $isActive = ($question['status'] == $statusId); ?>
                        <button type="submit" name="update_status" class="btn-sm" value="<?= $statusId ?>"
                            style="margin:0 5px; padding:8px 16px; border-radius:2px; border:1px solid #ccc;
                <?= $isActive ? 'background-color:#4CAF50; color:white;font-weight:bold;' : 'background-color:#f0f0f0; color:#333;' ?>">
                            <?= $label ?>
                        </button>
                        <br>
                        <br>
                    <?php endforeach; ?>
                </form>
            </div>

        </div>
    </div>
</div>



<input type="hidden" name="qb_id" value="<?= esc($question['qb_id']) ?>">
<input type="hidden" name="next_id" value="<?= esc($next_id) ?>">
<input type="hidden" name="prev_id" value="<?= esc($prev_id) ?>">

<!-- <div class="button-bar">
            <button type="submit" name="action" value="prev" class="btn btn-back" <?= !$prev_id ? 'disabled' : '' ?>>←
                Back</button>
            <button type="submit" name="action" value="next" class="btn btn-next" <?= !$next_id ? 'disabled' : '' ?>>Next
                →</button>
        </div> -->
</form>

<script>
    const correct = "<?= strtoupper(trim(esc($question['correct_answer']))) ?>";
    const answerMap = {
        'A': document.getElementById('ansA'),
        'B': document.getElementById('ansB'),
        'C': document.getElementById('ansC'),
        'D': document.getElementById('ansD')
    };
    if (answerMap[correct]) {
        answerMap[correct].classList.add('highlight');
    }
</script>

</body>

</html>