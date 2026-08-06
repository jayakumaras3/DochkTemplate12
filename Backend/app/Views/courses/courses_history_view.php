<style>
    .collapsible {

        color: white;
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.2);
        width: 100%;
        border: none;
        text-align: center;
        outline: none;
        font-size: 12px;
    }

    .contented {
        padding: 0 18px;
        display: none;
        overflow: hidden;
        background-color: rgb(118, 118, 118);

    }
</style>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>
                <?php echo lang('UI_Text.projects') ?> List
            </li><b>&nbsp;>&nbsp;</b>
            <li>
                <a href="<?php echo base_url('Project/projects') ?>"><?php echo $getCourseDetails['0']['projectname'] ?></a>
            </li><b>&nbsp;>&nbsp;</b>
            <li>
                <a href="<?php echo base_url('Project/project_details?projectid=' . $projectid) ?>"><?php echo $getCourseDetails['0']['course_name'] ?></a>
            </li><b>&nbsp;>&nbsp;</b>
         
        </ol>
    </div>
</div>
<div class="row">
    <?php $userlevel = session()->get('userlevel');
    $array  = array_map('intval', str_split($userlevel)); ?>

    <div class="col-md-12">
        <div class="block">
            <div class="x_panel">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Name</th>
                            <th>Notes</th>
                            <th>Stat</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Assetlink</th>
                            <th>%</th>
                            <th>Created By</th>
                            <th>Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        $user = session()->get('username');
                        if (!empty($coursehistory)) :
                            foreach ($coursehistory as $eachcoursehistoryData) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td width=5%><?php echo $j ?></td>
                                    <td><?php echo $eachcoursehistoryData['course_name']; ?></td>
                                    <td>
                                        <button type="button" class="collapsible" title="Notes" class="nav-link" data-widget="pushmenu">...</button>
                                        <div class="contented"><?php echo $eachcoursehistoryData['notes'] ?></div>
                                    </td>
                                    <td><?php echo $eachcoursehistoryData['colorstatusname']; ?></td>
                                    <td><?php echo $eachcoursehistoryData['type']; ?></td>
                                    <td><?php echo $eachcoursehistoryData['description']; ?></td>
                                    <td><?php echo $eachcoursehistoryData['assetlink']; ?></td>
                                    <td><?php echo $eachcoursehistoryData['completion'] ?></td>
                                    <td><?php echo $eachcoursehistoryData['createdby']; ?></td>
                                    <td><?php echo date('m-d-Y', $eachcoursehistoryData['createdon']); ?></td>
                                </tr>
                        <?php }
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var contented = this.nextElementSibling;
            if (contented.style.display === "block") {
                contented.style.display = "none";
            } else {
                contented.style.display = "block";

            }
        });
    }
</script>