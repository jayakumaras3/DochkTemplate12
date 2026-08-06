<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link_1) ?>">Pages</a></li>


                </ol>
            </div>
            <h4 class="page-title">Review Quiz</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="col-12 col-md-12 col-lg-12">

                <p class="text-muted font-13 mb-2"><b>Note : Correct answer highlight with <span style="color:green">green</span> colour</b></p>

                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th>Question</th>
                            <th>Type</th>
                            <!-- <th>Feedback</th> -->
                            <?php if (isset($maxOptions)) {
                                if ($maxOptions <= 6) {
                                    $maxOptions_count = 6;
                                } else {
                                    $maxOptions_count = $maxOptions;
                                }
                            ?>
                                <?php for ($i = 1; $i <= $maxOptions_count; $i++) { ?>
                                    <th>Option <?php echo $i ?></th>
                                    <!-- <th>Correct</th> -->
                                <?php } ?>
                            <?php } ?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($groupedData)) { 
                              $maxOptions_count =6;
                              ?>
                            <?php foreach ($groupedData as $q_id => $options) {
                                $question = $options[array_key_first($options)][0]['question'];  // Get the question from the first option group
                                $quiz_type = $options[array_key_first($options)][0]['quiz_type'];
                                if ($quiz_type == '112') {
                                    $quiztype = 'SCQ';
                                } elseif ($quiz_type == '115') {
                                    $quiztype = 'MCQ';
                                } else {
                                    $quiztype = '';
                                }
                                $feedback = $options[array_key_first($options)][0]['correct'];
                                $table_col = 0; 
                              
                                ?>

                                <tr>
                                    <td width="5%">
                                        <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/add_quiz_option_view') ?>" method="POST"><?= csrf_field() ?>
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="type" value="<?php echo $type; ?>">
                                            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                            <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                                            <input type="hidden" name="question_id" value="<?php echo $q_id; ?>"><button class="btn btn-outline-success btn-xs waves-effect waves-light"><?php echo $q_id ?></button>
                                        </form>
                                    </td>
                                    <td><?php echo $question ?></td>
                                    <td><?php echo $quiztype ?></td>
                                    <!-- <td><?php echo $feedback ?></td> -->
                                    <?php foreach ($options as $o_id => $optionGroup) {
                                        $table_col++;
                                        $option = $optionGroup[0];  ?>
                                        <td><?php if ($option['truefalse'] == 1) {
                                                echo '<p style="color:green">' . $option['values'] . '</p>';
                                            } else {
                                                echo $option['values'];
                                            } ?></td>
                                        <!-- <td><?php echo $option['truefalse'] == 1 ? 'TRUE' : '' ?></td> -->
                                    <?php } 
                                    for($i=0; $i<($maxOptions_count - $table_col); $i++) { ?>
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