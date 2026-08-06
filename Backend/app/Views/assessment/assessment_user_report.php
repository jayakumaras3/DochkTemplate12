<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url($course_header_link) ?>"><?php echo $course_header ?></a>
            </li><b> &nbsp;>&nbsp;</b>
            <li><a href="<?php echo base_url($header_link) ?>"><?php echo $header; ?></a>
            </li><b> &nbsp;>&nbsp;</b>
            <?php if ($header2 != '') { ?>
                <li><?php echo $header2; ?>
                </li><b> &nbsp;>&nbsp;</b>
            <?php } else {
            } ?>
            <li><a href="<?php echo base_url($header3_link) ?>"><?php echo $header3; ?></a>
            </li><b> &nbsp;>&nbsp;</b>
            <li class="active"><?php echo $header4; ?></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="content">

                    <table class="table  table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Questions</th>
                                <th>Option Selected</th>
                                <th>Scored</th>
                                <th>Updated on</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;
                            foreach ($getassessmentReport as $eachAssessmentReport) { 
                                $j = $j+1; ?>
                                <tr>
                                    <td><?php echo $j ?></td>
                                    <td><?php echo $eachAssessmentReport['question'] ?></td>
                                    <td><?php echo $eachAssessmentReport['values'] ?></td>
                                    <td><?php echo $eachAssessmentReport['scored'] ?></td>
                                    <td><?php echo date('m-d-Y', $eachAssessmentReport['last_updated_on']) ?></td>
                                </tr>
                            <?php  }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>