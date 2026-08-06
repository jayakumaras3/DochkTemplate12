<div class="col-xl-6 col-lg-12 order-lg-2 order-xl-1">
    <h4><?php echo $cat_name; ?></h4>
    <div class="card  ">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Course Name</td>
                        <td>Language</td>
                        <td>Duration</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($course_by_category != '') {
                        // echo count($course_by_category);
                        if (count($course_by_category) > 0) {
                            $j = 0;
                            foreach ($course_by_category  as $course) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo  $course['course_name']; ?></td>
                                    <td><?php echo  $course['language']; ?></td>
                                    <td><?php echo  $course['duration']; ?></td>
                                </tr>
                    <?php }
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>