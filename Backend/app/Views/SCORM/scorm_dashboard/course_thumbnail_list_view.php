<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <a href="<?= base_url('Settings/Settings/downloadAllThumbnails') ?>" class="btn btn-success mb-3">
                Download All Thumbnails (ZIP)
            </a>

            <p class="text-muted font-13 mb-4"></p>
            <?php

            $j = 0; ?>
            <table class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th class="center">#</th>
                        <th>Course Name</th>
                        <th>Filename</th>
                        <th>Thumbnail</th>
                        <th>Download</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coursedata as $data) {

                        $j = $j + 1; ?>
                        <tr>
                            <td class="center"><?= $j ?></td>
                            <td><?php echo $data['course_name'] ?></td>
                            <td><?php echo $data['thumbnail'] ?></td>
                            <td>
                                <?php if (isset($data['thumbnail']) && $data['thumbnail'] != '') {
                                    $thumbnail = base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'] . '/' . $data['thumbnail']);
                                } else {
                                    $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                                } ?>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <img style="border: 1px solid transparent; display: block;background: none;  border-color: rgb(0, 0, 0, 0.2);  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);"
                                            src="<?php echo $thumbnail ?>" alt="" class="img-fluid mx-auto d-block rounded">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                if (isset($data['thumbnail']) && $data['thumbnail'] != '') {
                                    $thumbnail = base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'] . '/' . $data['thumbnail']);
                                    $downloadName = $data['thumbnail'];
                                } else {
                                    $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                                    $downloadName = 'default_thumbnail.png';
                                }
                                ?>

                                <div class="row mb-3">
                                    <div class="col-md-12 text-center">
                                        <!-- <img
                                            style="border: 1px solid transparent; display: block; background: none; border-color: rgba(0,0,0,0.2); box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);"
                                            src="<?= $thumbnail ?>"
                                            alt="Thumbnail"
                                            class="img-fluid mx-auto d-block rounded"> -->

                                        <!-- Download Button -->
                                        <a
                                            href="<?= $thumbnail ?>"
                                            download="<?= $downloadName ?>"
                                            class="btn btn-sm btn-primary mt-2">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </td>


                        <?php
                    } ?>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>