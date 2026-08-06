
                                        <div class="col-md-12 col-sm-12 mb-2">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-2 form-group pull-right">
                                                    
                                                    <button type="button" class="btn btn-outline-success  btn-xs rounded-pill waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#signup-modal">Add New Question</button>

                                                </div>

                                                <div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <div class="modal-body">

                                                                <form class="px-3" action="<?php echo base_url('Assessment/trainings/addQuestions') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                                                                    <div class="mb-3">
                                                                        <label for="username" class="form-label">Question</label>
                                                                        <textarea class="form-control col-md-12" name="question" placeholder="Question" required></textarea>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="emailaddress" class="form-label">Type of Question</label>
                                                                        <select class="form-select" name="quiz_type">
                                                                            <option value="112">Single Choice</option>
                                                                            <option value="115">Multiple Choice</option>

                                                                        </select>
                                                                    </div>
                                                                    <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                                                    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                                                                    <input type="hidden" name="type" value="<?php echo $pagetype[0]['type'] ?>">
                                                                    <input type="hidden" name="typeval" value="8">
                                                                    <input type="hidden" name="returnUrl" value="1">

                                                                    <div class="mb-3 text-center">
                                                                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light rounded-pill" type="submit">Add New Question</button>
                                                                    </div>

                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-sm-2 form-group pull-right">

                                                    <form class="form-horizontal"
                                                        action="<?php echo base_url('Assessment/trainings/assessment_settings') ?>"
                                                        method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="type"
                                                            value="<?php echo $pagetype[0]['type'] ?>">
                                                        <input type="hidden" name="scourse_id"
                                                            value="<?php echo $scourse_id; ?>">
                                                        <input type="hidden" name="page_id"
                                                            value="<?php echo $pagerow['page_id'] ?>">
                                                        <input type="hidden" name="course_name"
                                                            value="<?php echo $pagerow['course_name']; ?>">
                                                        <input type="hidden" name="page_name"
                                                            value="<?php echo $pagerow['page_name']; ?>">
                                                        <div class="form-group">
                                                            <button type="submit"
                                                                class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light">
                                                                Settings</button>
                                                        </div>
                                                    </form>
                                                    </a>
                                                </div>

                                                <div class="col-md-2 col-sm-2 form-group pull-right">
                                                    <form class="form-horizontal"
                                                        action="<?php echo base_url('Assessment/trainings/export_questions_excel') ?>"
                                                        method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="type"
                                                            value="<?php echo $pagetype[0]['type'] ?>">
                                                        <input type="hidden" name="scourse_id"
                                                            value="<?php echo $scourse_id; ?>">
                                                        <input type="hidden" name="page_id"
                                                            value="<?php echo $pagerow['page_id'] ?>">
                                                        <input type="hidden" name="course_name"
                                                            value="<?php echo $pagerow['course_name']; ?>">
                                                        <div class="form-group">
                                                            <button type="submit"
                                                                class="btn btn-outline-danger  btn-xs rounded-pill waves-effect waves-light">Export
                                                                Questions</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-2 col-sm-2 form-group pull-right">
                                                    <form class="form-horizontal"
                                                        action="<?php echo base_url('Assessment/trainings/importQuestionsOptions_view') ?>"
                                                        method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="type"
                                                            value="<?php echo $pagetype[0]['type'] ?>">
                                                        <input type="hidden" name="scourse_id"
                                                            value="<?php echo $scourse_id; ?>">
                                                        <input type="hidden" name="page_id"
                                                            value="<?php echo $pagerow['page_id'] ?>">
                                                        <input type="hidden" name="course_name"
                                                            value="<?php echo $pagerow['course_name']; ?>">
                                                        <div class="form-group">
                                                            <button type="submit"
                                                                class="btn btn-outline-info  btn-xs rounded-pill waves-effect waves-light">Import
                                                                Questions</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-2 col-sm-2 form-group pull-right">
                                                    <form class="form-horizontal"
                                                        action="<?php echo base_url('Assessment/trainings/review_quiz') ?>"
                                                        method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="scourse_id"
                                                            value="<?php echo $scourse_id; ?>">
                                                        <input type="hidden" name="page_id"
                                                            value="<?php echo $pagerow['page_id'] ?>">
                                                        <input type="hidden" name="type" value="<?php echo $pagerow['type'] ?>">
                                                        <div class="form-group">
                                                            <button type="submit"
                                                                class="btn btn-outline-primary  btn-xs rounded-pill waves-effect waves-light">Review
                                                                Quiz</button>
                                                        </div>
                                                    </form>
                                                </div>

</div>
                                        </div>

                                        <style>
                                            .question-cell {
                                                max-width: 500px;
                                                padding-left: 100px;
                                                
                                                overflow: hidden;
                                                
                                                white-space: nowrap;

                                                vertical-align: top;
                                            }
                                        </style>
                                        <div class="row">
                                            <?php $userlevel = session()->get('userlevel');
                                            $array = array_map('intval', str_split($userlevel)); ?>
                                            <div class="card">
                                                <div class="card-body">
                                                    <p class="text-muted font-13 mb-2"></p>
                                                    <table id="alternative-page-datatable"
                                                        class="table dt-responsive nowrap w-100">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Question</th>
                                                                <th width="5%">Edit</th>
                                                                <?php if ($pagetype[0]['type'] == '4') { ?>
                                                                    <th  width="5%">Copy</th>
                                                                <?php } ?>
                                                                <th width="5%">Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $j = 0;
                                                            if (isset(($getQuestiondata)) && !empty($getQuestiondata)) {
                                                                foreach ($getQuestiondata as $eachQuestion) {
                                                                    $j = $j + 1;

                                                            ?>
                                                                    <tr>
                                                                        <td width="5%"><?php echo $j; ?></td>

<td class="question-cell">
                                                                            <?php echo $eachQuestion['question']; ?>
                                                                        </td>

<td >
                                                                            <form class="form-horizontal"
                                                                                action="<?php echo base_url('Assessment/trainings/add_quiz_option_view') ?>"
                                                                                method="POST"><?= csrf_field() ?>
                                                                                <input type="hidden" name="type"
                                                                                    value="<?php echo $pagetype[0]['type']; ?>">
                                                                                <input type="hidden" name="scourse_id"
                                                                                    value="<?php echo $scourse_id; ?>">
                                                                                <input type="hidden" name="page_id"
                                                                                    value="<?php echo $eachQuestion['page_id']; ?>">
                                                                                <input type="hidden" name="question_id"
                                                                                    value="<?php echo $eachQuestion['q_id']; ?>">
                                                                                <button class="btn btn-outline-warning waves-effect btn-xs waves-light rounded-pill"><span
                                                                                        class="mdi mdi-square-edit-outline"></span></button>
                                                                            </form>
                                                                        </td>
                                                                        <?php if ($pagetype[0]['type'] == '4') { ?>
                                                                            <td >
                                                                                <form class="form-horizontal"
                                                                                    action="<?php echo base_url($copyQuestion_link) ?>"
                                                                                    method="POST"><?= csrf_field() ?>
                                                                                    <input type="hidden" name="scourse_id"
                                                                                        value="<?php echo $scourse_id; ?>">
                                                                                    <input type="hidden" name="question_id"
                                                                                        value="<?php echo $eachQuestion['q_id']; ?>">
                                                                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light rounded-pill"><span
                                                                                            class="mdi mdi-content-copy"></span></button>
                                                                                </form>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <td>
                                                                            <form class="form-horizontal"
                                                                                action="<?php echo base_url($quizdelete_link) ?>"
                                                                                method="POST"><?= csrf_field() ?>
                                                                                <input type="hidden" name="question_id"
                                                                                    value="<?php echo $eachQuestion['q_id']; ?>">
                                                                                <button type="submit"
                                                                                    onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"
                                                                                    class="btn btn-outline-danger waves-effect btn-xs waves-light rounded-pill"><span
                                                                                        class="mdi mdi-trash-can-outline"></span></button>
                                                                            </form>
                                                                        </td>

                                                                    </tr>
                                                            <?php
                                                                }
                                                            } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
