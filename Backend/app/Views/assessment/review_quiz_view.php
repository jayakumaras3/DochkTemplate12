<style>
    .rq-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    }

    [data-bs-theme="dark"] .rq-card {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
    }

    .rq-legend {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--ct-tertiary-bg);
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 13px;
        margin-bottom: 16px;
    }

    .rq-legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #0acf97;
        display: inline-block;
    }

    .rq-table thead th {
        font-weight: 700;
        font-size: 12.5px;
        color: var(--ct-body-color);
        background-color: rgba(var(--ct-primary-rgb), 0.06);
        border: none;
        white-space: nowrap;
    }

    .rq-table td {
        font-size: 13px;
        vertical-align: middle;
    }

    .rq-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 600;
        white-space: nowrap;
    }

    .rq-type-scq {
        background: rgba(102, 88, 221, 0.12);
        color: #6658dd;
    }

    .rq-type-mcq {
        background: rgba(10, 207, 151, 0.12);
        color: #0acf97;
    }

    .rq-option-correct {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: #0acf97;
        font-weight: 600;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link_1) ?>"><?php echo $header_1 ?></a></li>
                </ol>
            </div>
            <h4 class="page-title">Review Quiz</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card rq-card">
            <div class="card-body">
                <div class="rq-legend">
                    <span class="rq-legend-dot"></span>
                    <span>Correct answer</span>
                </div>

                <div class="table-responsive">
                    <table class="table rq-table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th>Question</th>
                                <th>Type</th>
                                <?php if (isset($maxOptions)) {
                                    $maxOptions_count = $maxOptions;
                                ?>
                                    <?php for ($i = 1; $i <= $maxOptions_count; $i++) { ?>
                                        <th>Option <?php echo $i ?></th>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($groupedData)) {
                                $maxOptions_count = $maxOptions ?? 4;
                            ?>
                                <?php foreach ($groupedData as $q_id => $options) {
                                    $question = $options[array_key_first($options)][0]['question'];
                                    $quiz_type = $options[array_key_first($options)][0]['quiz_type'];
                                    if ($quiz_type == '112') {
                                        $quiztype = 'SCQ';
                                        $quiztypeClass = 'rq-type-scq';
                                    } elseif ($quiz_type == '115') {
                                        $quiztype = 'MCQ';
                                        $quiztypeClass = 'rq-type-mcq';
                                    } else {
                                        $quiztype = '';
                                        $quiztypeClass = '';
                                    }
                                    $table_col = 0;
                                ?>
                                    <tr>
                                        <td width="5%">
                                            <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/add_quiz_option_view') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="type" value="<?php echo $type; ?>">
                                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                                <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                                                <input type="hidden" name="question_id" value="<?php echo $q_id; ?>">
                                                <button class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light"><?php echo $q_id ?></button>
                                            </form>
                                        </td>
                                        <td><?php echo $question ?></td>
                                        <td>
                                            <?php if ($quiztype) { ?>
                                                <span class="rq-type-badge <?php echo $quiztypeClass ?>"><?php echo $quiztype ?></span>
                                            <?php } ?>
                                        </td>
                                        <?php foreach ($options as $o_id => $optionGroup) {
                                            $table_col++;
                                            if ($table_col > $maxOptions_count) {
                                                break;
                                            }
                                            $option = $optionGroup[0];
                                        ?>
                                            <td>
                                                <?php if ($option['truefalse'] == 1) { ?>
                                                    <span class="rq-option-correct"><i class="mdi mdi-check-circle"></i> <?php echo $option['values'] ?></span>
                                                <?php } else {
                                                    echo $option['values'];
                                                } ?>
                                            </td>
                                        <?php }
                                        for ($i = 0; $i < ($maxOptions_count - min($table_col, $maxOptions_count)); $i++) { ?>
                                            <td></td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
