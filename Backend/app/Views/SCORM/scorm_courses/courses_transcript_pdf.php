<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                </ol>
            </div>
            <h4 class="page-title">Download Transcript</h4>
        </div>
    </div>
</div>
<?php
$base = base_url();
//print_r($base);
//exit();
if ($base == 'http://localhost:8888/projects_dochek/') {
    $baseloc = '/Users/pchandran/Sites/projects_dochek/';
}
if ($base == 'http://localhost/projects_dochek/') {
    $baseloc = 'D:/wampp/www/projects_dochek/';
}
if ($base == 'https://dochek.com/') {
    $baseloc = '/var/www/html/';
}
if ($base == 'http://localhost/DOCHEK/') {
    $baseloc = 'D:/wampp/www/DOCHEK/';
}
if ($base == 'https://staging.dochek.com/') {
    $baseloc = '/var/www/html/DOCHEK/';
}
if ($base == 'http://localhost/DOCHEKDOTCOM/') {
    $baseloc = 'D:/wampp/www/DOCHEKDOTCOM/';
}
if ($base == 'http://172.16.2.218/DOCHEK/') {
    $baseloc = '/var/www/DOCHEK/';
}
if ($base == 'http://172.16.2.218/DOCHEK/') {
    $baseloc = '/var/www/DOCHEK/';
}
?>
<div class="row">
    <div class="col-lg-12 mb-3">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Code</th>
                            <th>Course name</th>
                            <th>Download</th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($coursedata as $data) {
                            $j = $j + 1;
                            ?>
                            <tr>
                                <td><?php echo $j ?></td>
                                <td><?php echo $data['course_code'] ?></td>
                                <td><?php echo $data['course_name'] ?></td>

                                <?php
                                $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/PDF';
                                // print_r($folderloc);
                                if (is_dir($folderloc)) {
                                    $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                                    $sno = 0;

                                    foreach ($files2 as $key => $value) {
                                        if (strlen($value) > 3) {

                                            $dontshow = 0;
                                            $file_parts = pathinfo($value);
                                            if ($file_parts['extension'] != 'DS_Store') {
                                                $sno++;
                                                ?>

                                                <td>
                                                    <a
                                                        href="<?php echo base_url('assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/PDF/' . $value); ?> " target="_blank" ><?php echo $value ?></a>
                                                </td>
                                                <?php
                                            }
                                        }
                                    }
                                } ?>

                            </tr>
                            <?php
                        } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>$(document).ready(function () {
        $('#dynamic-table').DataTable();
    });
</script>