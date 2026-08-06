<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                </ol>
            </div>
            <h4 class="page-title">Assessment Report</h4>
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
                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Quiz name</th>
                            <th>No of questions</th>
                            <th>Attempts allowed</th>
                            <th>Passing %</th>
                            <th>Max questions</th>
                            <th>Duration (Min)</th>
                            <th>Pre-Attempt / Post-Attempt </th>
                            <th>Pre-Test / Post-Test</th>
                            <th>Randomize Options</th>
                            <th>Randomize Questions</th>
                            <th>Free navigation</th>
                            <th>Page level course completion</th>
                            <th>Certificate Enable/Disable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($assessment_report as $data) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $data['question']; ?></td>
                                <td><?php echo $data['No_of_questions']; ?></td>
                                <td><?php echo $data['Attempt_allowed']; ?></td>
                                <td><?php echo $data['passing_per']; ?></td>
                                <td><?php echo $data['max_question']; ?></td>
                                <td><?php echo $data['Duration']; ?></td>
                                <td><?php echo $data['PreAttempt_PostAttempt']; ?></td>
                                <td><?php echo $data['PreTest_PostTest']; ?></td>
                                <td><?php echo $data['Randomize_Options']; ?></td>
                                <td><?php echo $data['Randomize_Questions']; ?></td>
                                <td><?php echo $data['free_navigation']; ?></td>
                                <td><?php echo $data['page_level_course_completion']; ?></td>
                                <td><?php echo $data['Certificate_Enabled']; ?></td>
                            </tr>
                        <?php } ?>
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