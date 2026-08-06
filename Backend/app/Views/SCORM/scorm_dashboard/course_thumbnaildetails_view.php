<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <p class="text-muted font-13 mb-4"></p>
            <?php

            $j = 0; ?>
            <table  class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th class="center">#</th>
                        <th>Course Name</th>
                        <th>Language</th>
                        <th>Thumbnail Path</th>


                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coursedata as $data) {

                        $j = $j + 1; ?>
                        <tr>
                            <td class="center"><?= $j ?></td>
                            <td><?php echo $data['course_name'] ?></td>
                            <td><?php echo $data['language'] ?></td>
                            <td>
                                <?php if (isset($data['thumbnail']) && $data['thumbnail'] != '') {
                                    echo  base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'] . '/' . $data['thumbnail']);
                                } else {

                                } ?>

                             
                            </td>

                            <?php
                    } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>