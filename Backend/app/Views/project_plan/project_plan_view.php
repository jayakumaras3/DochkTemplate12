<?php
$userlevel = session()->get('userlevel');
// \print_r( $userlevel);
$array  = explode(',', $userlevel);
?>
<style>
    tr[color_code='Delayed'] {
        color: red
    }

    tr[complete='incomplete'] {
        color: orange
    }

    .collapsible {

        color: white;
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.4);
        width: 100%;
        border: none;
        text-align: center;
        outline: none;
        font-size: 12px;
    }

    .contented {
        color: white;
        padding: 0 18px;
        display: none;
        overflow: hidden;
        background-color: rgba(0, 0, 0, 0.4);

    }

    .tablescroll {
        height: 500px;
        overflow-y: scroll;
    }

    th {
        top: 0;
        position: sticky;
        color: red;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn') ?>">My UCN</a></li>

                </ol>
            </div>
            <h4 class="page-title">Project Plan - <?php echo $projectDetails[0]['projectname'] ?> </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">

        <?php $gen_random = 420 . rand(25, 50) . rand(100, 1000);
        $temp_id = password_hash($gen_random, PASSWORD_DEFAULT);
        $dealCrypt = crypt($projectid, '');
        $ciphering = "AES-128-CTR";
        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1234567891011121';

        // Store the encryption key
        $encryption_key = "GeeksforGeeks";

        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt(
            $projectid,
            $ciphering,
            $encryption_key,
            $options,
            $encryption_iv
        );
        $temp_id  =  preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $encryption . '_' . $temp_id);

        $user_url = base_url('usergraph/projectplanGraph/' . $temp_id);
        $projectplan_url = base_url('userprojectplan/getprojectplan/' . $temp_id); ?>

        <!-- <div class="block">
            <div class="content">
                <div class="x_panel">
                    <p id="p1"> <?php echo $user_url ?> <button onclick="copyToClipboard('#p1')" class="btn btn-sm btn-primary">Copy</button></p>
                </div>
            </div>
        </div> -->

        <!-- <?php if (in_array("6", $array) || in_array("4", $array)) {
                    if (count($templatedetailsdata) <= 0) {  ?>

                <div class="block">

                    <div class="content">
                        <div class="x_panel">
                            <form class="form" action="<?php echo base_url('Project/project_plan/addtemplate?projectid=' . $projectid . '&course_id=' . $projectid); ?>" method="POST"><?= csrf_field() ?>
                                <div class="col-md-4">
                                    <select name="template_type" class="form-control">
                                       
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-info btn-sm form-control">
                                        <i class="ace-icon fa fa-key bigger-110"></i> Add Item
                                    </button>
                                </div>
                                <?php if (isset($validationData)) : ?>
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            <?= $validationData->listErrors() ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>

        <?php }
                }
        ?> -->
        <div>
            <?php if (in_array("6", $array) || in_array("4", $array)) {  ?>

                <div class="card">
                    <div class="card-body">
                        <form class="form" action="<?php echo base_url('Project/project_plan/addheader'); ?>" method="POST"><?= csrf_field() ?>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="header_name" placeholder="Header Name" value="" />
                            </div><br />
                            <div class="col-md-12">
                                <input type="hidden" name="course_id" value="<?php echo $projectid ?>">
                                <input type="hidden" name="projectid" value="<?php echo $projectid ?>">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    Add Project Plan Header
                                </button>
                            </div>
                            <?php if (isset($validationData)) : ?>
                                <div class="col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validationData->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
        </div>
    </div>
<?php } ?>
<?php if (in_array("6", $array) || in_array("4", $array)) {  ?>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project/project_plan/bulknewpage'); ?>" method="POST" enctype="multipart/form-data"><?= csrf_field() ?>
                    <div class="row mb-1">
                        <div class="form-group col-md-6">
                            Bulk Upload
                        </div>
                        <div class="form-group col-md-6">
                            <a href="<?php echo base_url('assets/assets/project_plan/project_plan_sample.xls') ?>" target="_blank" class="btn btn-outline-info waves-effect btn-sm waves-light">Sample</a><br />
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input-group file">
                                <input type="file" name="file" id="file" accept=".xls" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <input type="hidden" name="course_id" value="<?php echo $projectid ?>">
                        <input type="hidden" name="projectid" value="<?php echo $projectid ?>">
                        <input type="hidden" name="bulknewpage" value="1">
                        <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php } ?>
<div class="col-md-3">
    <div class="card">


        <div class="card-body">
            <div class="row mb-1" style="text-align:right;">
                <p id="p1" style="display:none"><?php echo $projectplan_url ?></p>
                <a onclick="copyToClipboard('#p1')" title="Copy Project Plan Link ">
                    <span class="icon-link"></span></a>
            </div>
            <!-- <script>
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const page_type = urlParams.get('temp_id')
            </script> -->

            <body>
                <?php
                // $temp_id = "<script>var n=page_type; document.write(n);</script>";
                //  echo $temp_id;
                ?>
                <div class="row  mb-1">
                    <!-- <form action="<?php echo $projectplan_url ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="temp_id" value="<?php echo $temp_id ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger waves-effect btn-sm waves-light mb-0">
                        Project Plan View </button>
                    </form> -->
                    <a href="<?php echo $projectplan_url ?>" target="_blank" class="btn btn-sm btn-outline-success waves-effect btn-sm waves-light mb-0 ">
                        Project Plan View </a>
                </div>
                <div class="row  mb-1">
                    <form action="<?php echo base_url('Project/project_plan/newexportcomments') ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="course_id" value="<?php echo $projectid ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger waves-effect btn-sm waves-light col-md-12">
                            Export Project Plan</button>
                    </form>

                    <!-- <a href="<?php echo base_url('Project/project_plan/newexportcomments?course_id=' . $projectid) ?>" target="_blank" class="btn btn-sm btn-outline-danger waves-effect btn-sm waves-light mb-0">Export Project Plan</a> -->
                </div>



        </div>
    </div>
</div>

</div>

<div class="row">
    <div class="col-md-12">

    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="tablescroll">
                    <table id="example1" class="table table-sm table-bordered table-striped">
                        <thead style="background-color: white;">
                            <tr>
                                <th width="2%">#</th>
                                <th width="5%">Assign</th>
                                <th width="15%">Item</th>
                                <th width="5%">%</th>
                                <th width="5%">Day</th>
                                <th width="12%">Start date</th>
                                <th width="5%">Days</th>
                                <th width="12%">End date</th>
                                <th width="5%">Task</th>
                                <!-- <th width="10%">Note</th> -->
                                <!-- <th width="5%">Link</th> -->
                                <th width="10%">Status</th>
                                <?php if (in_array("6", $array) || in_array("4", $array)) {  ?>
                                    <!-- <th width="5%">His</th> -->
                                    <!-- <th width="5%">Link</th> -->
                                    <th width="5%">Del</th>
                                <?php } else {
                                } ?>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($headerdata as $eachheaderdata) { ?>
                                <tr>
                                    <td colspan="8" style="padding-bottom:0">
                                        <div style="text-align:center;">
                                            <?php if (in_array("6", $array) || in_array("4", $array)) {  ?>
                                                <a type="button" href="#modal<?php echo $eachheaderdata['id_ph'] ?>" class="btn btn-sm btn-outline-dark waves-effect btn-sm waves-light mb-1" data-bs-toggle="modal" data-bs-target="#modal<?php echo $eachheaderdata['id_ph'] ?>">
                                                    <?php echo $eachheaderdata['header_name'] ?> <i class="fa fa-plus"></i>
                                                </a>
                                            <?php } else { ?>
                                                <?php echo $eachheaderdata['header_name'] ?>
                                            <?php } ?>
                                        </div>

                                    </td>
                                    <?php if (in_array("6", $array) || in_array("4", $array)) {  ?>
                                        <td width="5%" style="padding-bottom:0">
                                            <form action="<?php echo base_url('Project/project_plan/editplanheader_view') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="course_id" value="<?php echo $projectid ?>">
                                                <input type="hidden" name="projectid" value="<?php echo $projectid ?>">
                                                <input type="hidden" name="id_ph" value="<?php echo $eachheaderdata['id_ph'] ?>">
                                                <button type="submit" class="btn btn-sm btn-default">
                                                    <span class="icon-pencil"></span></button>
                                            </form>
                                        </td>
                                        <!-- <a href="<?php echo base_url('Project/project_plan/editplanheader_view?id_ph=' . $eachheaderdata['id_ph'] . '&projectid=' . $projectid . '&course_id=' . $projectid); ?>" title="Edit"><button class="widget-icon  btn-default"><i class="icon-pencil"></i></button></a></td> -->
                                        <td width="5%" style="padding-bottom:0">
                                            <form action="<?php echo base_url('Project/project_plan/deleteplanheader_view') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="course_id" value="<?php echo $projectid ?>">
                                                <input type="hidden" name="projectid" value="<?php echo $projectid ?>">
                                                <input type="hidden" name="id_ph" value="<?php echo $eachheaderdata['id_ph'] ?>">
                                                <button type="submit" class="btn btn-sm btn-default" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')">
                                                    <span class="icon-trash"></span></button>
                                            </form>
                                        </td>
                                        <!-- <td width="5%" style="padding-bottom:0"><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('Project/project_plan/deleteplanheader_view?id_ph=' . $eachheaderdata['id_ph'] . '&projectid=' . $projectid . '&course_id=' . $projectid); ?>" title="Delete"><button class="btn btn-sm btn-default"><i class="icon-trash"></i></button></a></td> -->
                                    <?php } ?>
                                </tr>
                                <div class="modal fade" id="modal<?php echo $eachheaderdata['id_ph'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel6" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content tx-14">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="exampleModalLabel6">Add Project Plan Details</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true"></span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php if (in_array("6", $array) || in_array("4", $array)) {  ?>

                                                    <div class="card">

                                                        <div class="card-body">
                                                            <form class="form" action="<?php echo base_url('Project/project_plan/addtimeline'); ?>" method="POST"><?= csrf_field() ?>
                                                                <div class="col-md-12">
                                                                    <select name="item_type" class="form-control">
                                                                        <option value="1">TQ</option>
                                                                        <option value="<?php echo $clientdata[0]['client'] ?>"><?php echo $clientdata[0]['client_name'] ?></option>

                                                                    </select>
                                                                </div><br />
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" name="item_description" placeholder="Description" value="" required/>
                                                                </div><br />
                                                                <div class="col-md-12">
                                                                    <input type="number" class="form-control" name="completion" placeholder="%" value="" />
                                                                </div><br />
                                                                <div class="col-md-12">
                                                                    <input id="birthday" name="start_date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" required="required" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                                                                    <script>
                                                                        function timeFunctionLong(input) {
                                                                            setTimeout(function() {
                                                                                input.type = 'text';
                                                                            }, 60000);
                                                                        }
                                                                    </script>
                                                                </div><br />

                                                                <div class="col-md-12">
                                                                    <input type="text" id='days' class="form-control" name="duration" placeholder="Duration" value="" required />
                                                                </div><br />
                                                                <div class="col-md-12">
                                                                    <input type="hidden" class="form-control" name="projectid" value="<?php echo $projectid ?>" />
                                                                    <input type="hidden" class="form-control" name="course_id" value="<?php echo $projectid ?>" />
                                                                    <input type="hidden" class="form-control" name="header" value="<?php echo $eachheaderdata['id_ph'] ?>" />
                                                                    <button type="submit" class="btn btn-info btn-sm form-control">
                                                                        Add
                                                                    </button>
                                                                </div>
                                                                <?php if (isset($planvalidation)) : ?>
                                                                    <div class="col-md-12">
                                                                        <div class="alert alert-danger" role="alert">
                                                                            <?= $planvalidation->listErrors() ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </form>

                                                        </div>
                                                    </div>

                                                <?php } ?>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if (in_array("6", $array) || in_array("4", $array)) {  ?>
                                    <?php $j = 0;
                                    foreach ($dealtimelineData as $eachdealtimeline) {
                                        $j = $j + 1;
                                        if ($eachheaderdata['id_ph'] == $eachdealtimeline['header']) {
                                            $incomplete = ($eachdealtimeline['end_date'] < date('Y-m-d') && ($eachdealtimeline['levelname'] != 'Delayed') && ($eachdealtimeline['levelname'] != 'Completed')) ? 'incomplete' : '';
                                            // print_r($complete);
                                    ?>
                                            <tr color_code='<?php echo $eachdealtimeline['levelname'] ?>' complete='<?php echo $incomplete ?>'>
                                                <td width="2%"><?php echo $eachdealtimeline['dt_id']  ?></td>
                                                <td width="5%"><?php echo $eachdealtimeline['itemtypename'] ?></td>
                                                <td width="15%" contentEditable="true" onBlur="updateDate(this,'item_description','<?php echo $eachdealtimeline['dt_id'] ?>')"><?php echo $eachdealtimeline['item_description'] ?></td>
                                                <td width="5%" contentEditable="true" onBlur="updateDate(this,'completion','<?php echo $eachdealtimeline['dt_id'] ?>')"><?php echo $eachdealtimeline['completion'] ?></td>
                                                <td width="5%" contentEditable="true" onBlur="updateDate(this,'start_day','<?php echo $eachdealtimeline['dt_id'] ?>')"><?php echo $eachdealtimeline['start_day'] ?></td>

                                                <td width="10%"> <button type="button" class="collapsible" title="toggle" class="nav-link" data-widget="pushmenu"><?php if ($eachdealtimeline['start_date'] != '0000-00-00') {
                                                                                                                                                                        echo date('d-M-y', strtotime($eachdealtimeline['start_date']));
                                                                                                                                                                    } else {
                                                                                                                                                                        echo '00-00-00';
                                                                                                                                                                    } ?></button>
                                                    <div class="contented">
                                                        <input name="date" id="myInput_<?php echo $eachdealtimeline['dt_id']; ?>" value="<?php echo htmlspecialchars($eachdealtimeline['start_date']); ?>" type="date" />
                                                        <div id="myDiv_<?php echo $eachdealtimeline['dt_id']; ?>"></div>
                                                    </div>
                                                </td>
                                                <td width="5%" contentEditable="true" onBlur="updateDate(this,'duration','<?php echo $eachdealtimeline['dt_id'] ?>')"><?php echo $eachdealtimeline['duration'] ?></td>

                                                <td width="10%"><?php if ($eachdealtimeline['end_date'] != '0000-00-00') {
                                                                    echo date('d-M-y', strtotime($eachdealtimeline['end_date']));
                                                                } else {
                                                                    echo '00-00-00';
                                                                } ?></button>

                                                </td>
                                                <td width="5%">
                                                    <form action="<?php echo base_url('Task/Task_master') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="projectid" value="<?php echo $eachdealtimeline['fk_course_id'] ?>">
                                                        <input type="hidden" name="item_description" value="<?php echo $eachdealtimeline['item_description'] ?>">
                                                        <input type="hidden" name="dt_id" value="<?php echo $eachdealtimeline['dt_id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-default">
                                                            <i class="mdi mdi-clipboard-check-outline font-20"></i></button>
                                                    </form>
                                                </td>
                                                <!--  <td width="10%">
                                                    <button type="button" class="collapsible" title="toggle" class="nav-link" data-widget="pushmenu">...</button>
                                                    <div class="contented" contentEditable="true" onBlur="updateDate(this,'note','<?php echo $eachdealtimeline['dt_id'] ?>')"><?php echo $eachdealtimeline['note'] ?></div>
                                                </td> -->
                                                <!-- <td width="10%"><?php if ($eachdealtimeline['link'] == 0) {
                                                                            echo '';
                                                                        } else {
                                                                            echo $eachdealtimeline['link'];
                                                                        }
                                                                        ?>
                                                </td> -->
                                                <td width="15%">
                                                    <button type="button" class="collapsible" title="toggle" class="nav-link" data-widget="pushmenu"><?php echo $eachdealtimeline['levelname']; ?></button>
                                                    <div class="contented">
                                                        <select id="leveldata" onchange="myFunction(this.value,'<?php echo $eachdealtimeline['dt_id'] ?>');">
                                                            <?php if (!empty($leveldata)) { ?>
                                                                <option value="">-- select Status --</option>
                                                                <?php foreach ($leveldata as $eachstatusdata) { ?>
                                                                    <option value="<?php echo $eachstatusdata['id_d'] ?>"><?php echo $eachstatusdata['name'] ?></option>
                                                            <?php  }
                                                            } ?>
                                                        </select>

                                                    </div>
                                                </td>
                                                <!-- <td width="5%">
                                                    <form action="<?php echo base_url('Project/project_plan/dealhistorytimeline') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="course_id" value="<?php echo $projectid ?>">
                                                        <input type="hidden" name="project_id" value="<?php echo $projectid ?>">
                                                        <input type="hidden" name="dt_id" value="<?php echo $eachdealtimeline['dt_id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-default">
                                                            <span class="icon-clock"></span></button>
                                                    </form>
                                                </td> -->
                                                <!-- <a href="<?php echo base_url('Project/project_plan/dealhistorytimeline/' . $eachdealtimeline['dt_id'] . '/' . $eachdealtimeline['fk_course_id'] . '/' . $projectid) ?>" title=" History"><button class="widget-icon  btn-info"><i class="icon-time"></i></button></a> -->
                                                </td>
                                                <!-- <td width="5%">
                                                    <form action="<?php echo base_url('Project/project_plan/link') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="course_id" value="<?php echo $projectid ?>">
                                                        <input type="hidden" name="project_id" value="<?php echo $projectid ?>">
                                                        <input type="hidden" name="dt_id" value="<?php echo $eachdealtimeline['dt_id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-default">
                                                            <span class="icon-link"></span></button>
                                                    </form>
                                                </td> -->
                                                <!-- <a href="<?php echo base_url('Project/project_plan/link/' . $eachdealtimeline['dt_id'] . '/' . $eachdealtimeline['fk_course_id'] . '/' . $projectid) ?>" title="Link"><button class="widget-icon  btn-default"><i class="icon-link"></i></button></a></td> -->
                                                <td width="5%">
                                                    <form action="<?php echo base_url('Project/project_plan/deletedealtimeline') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="course_id" value="<?php echo $projectid ?>">
                                                        <input type="hidden" name="projectid" value="<?php echo $projectid ?>">
                                                        <input type="hidden" name="dt_id" value="<?php echo $eachdealtimeline['dt_id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-default" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')">
                                                            <span class="icon-trash"></span></button>
                                                    </form>
                                                </td>
                                            </tr>

                                        <?php }
                                    }
                                } elseif (in_array("44", $array) || in_array("45", $array)) {
                                    $j = 0;
                                    foreach ($dealtimelineData as $eachdealtimeline) {
                                        $j = $j + 1;
                                        if ($eachheaderdata['id_ph'] == $eachdealtimeline['header']) {
                                        ?>
                                            <tr color_code='<?php echo $eachdealtimeline['levelname'] ?>'>
                                                <td width="2%"><?php echo $eachdealtimeline['dt_id']  ?></td>
                                                <td width="5%"><?php echo $eachdealtimeline['itemtypename'] ?></td>
                                                <td width="15%" contentEditable="true" onBlur="updateDate(this,'item_description','<?php echo $eachdealtimeline['dt_id'] ?>')"><?php echo $eachdealtimeline['item_description'] ?></td>
                                                <td width="5%" contentEditable="true" onBlur="updateDate(this,'completion','<?php echo $eachdealtimeline['dt_id'] ?>')"><?php echo $eachdealtimeline['completion'] ?></td>
                                                <td width="5%" contentEditable="true" onBlur="updateDate(this,'start_day','<?php echo $eachdealtimeline['dt_id'] ?>')"><?php echo $eachdealtimeline['start_day'] ?></td>

                                                <td width="10%"> <button type="button" class="collapsible" title="toggle" class="nav-link" data-widget="pushmenu"><?php if ($eachdealtimeline['start_date'] != '0000-00-00') {
                                                                                                                                                                        echo date('m-d-y', strtotime($eachdealtimeline['start_date']));
                                                                                                                                                                    } else {
                                                                                                                                                                        echo '00-00-00';
                                                                                                                                                                    } ?></button>
                                                    <div class="contented" onBlur="updateDate(this,'start_date','<?php echo $eachdealtimeline['dt_id'] ?>')">
                                                        <input name="date" class="datepicker-input" value="<?php echo $eachdealtimeline['start_date'] ?>" type="hidden" />
                                                        <div class="date"><?php echo $eachdealtimeline['start_date'] ?> <i class="fa fa-calendar" aria-hidden="true"></i></div>
                                                    </div>
                                                </td>
                                                <td width="5%" contentEditable="true" onBlur="updateDate(this,'duration','<?php echo $eachdealtimeline['dt_id'] ?>')"><?php echo $eachdealtimeline['duration'] ?></td>

                                                <td width="10%"><?php if ($eachdealtimeline['end_date'] != '0000-00-00') {
                                                                    echo date('m-d-y', strtotime($eachdealtimeline['end_date']));
                                                                } else {
                                                                    echo '00-00-00';
                                                                } ?></button>

                                                </td>
                                                <td width="10%">
                                                    <button type="button" class="collapsible" title="toggle" class="nav-link" data-widget="pushmenu">...</button>
                                                    <div class="contented" contentEditable="true" onBlur="updateDate(this,'note','<?php echo $eachdealtimeline['dt_id'] ?>')"><?php echo $eachdealtimeline['note'] ?></div>
                                                </td>
                                                <td width="10%"><?php if ($eachdealtimeline['link'] == 0) {
                                                                    echo '';
                                                                } else {
                                                                    echo $eachdealtimeline['link'];
                                                                }
                                                                ?>
                                                </td>
                                                <td width="15%">
                                                    <button type="button" class="collapsible" title="toggle" class="nav-link" data-widget="pushmenu"><?php echo $eachdealtimeline['levelname']; ?></button>
                                                    <div class="contented">
                                                        <select id="leveldata" onchange="myFunction(this.value,'<?php echo $eachdealtimeline['dt_id'] ?>');">
                                                            <?php if (!empty($leveldata)) { ?>
                                                                <option value="">-- select Status --</option>
                                                                <?php foreach ($leveldata as $eachstatusdata) { ?>
                                                                    <option value="<?php echo $eachstatusdata['id_d'] ?>"><?php echo $eachstatusdata['name'] ?></option>
                                                            <?php  }
                                                            } ?>
                                                        </select>

                                                    </div>
                                                </td>

                                            <?php }
                                    }
                                } else {
                                    $j = 0;
                                    foreach ($dealtimelineData as $eachdealtimeline) {
                                        $j = $j + 1;
                                        if ($eachheaderdata['id_ph'] == $eachdealtimeline['header']) {
                                            ?>
                                            <tr color_code='<?php echo $eachdealtimeline['levelname'] ?>'>
                                                <td><?php echo $eachdealtimeline['dt_id']  ?></td>
                                                <td><?php echo $eachdealtimeline['itemtypename'] ?></td>
                                                <td><?php echo $eachdealtimeline['item_description'] ?></td>
                                                <td><?php echo $eachdealtimeline['completion'] ?></td>
                                                <td><?php echo $eachdealtimeline['start_day'] ?></td>
                                                <td> <?php if ($eachdealtimeline['start_date'] != '0000-00-00') {
                                                            echo date('m-d-y', strtotime($eachdealtimeline['start_date']));
                                                        } else {
                                                            echo '00-00-00';
                                                        } ?></td>
                                                <td><?php echo $eachdealtimeline['duration'] ?></td>

                                                <td><?php if ($eachdealtimeline['end_date'] != '0000-00-00') {
                                                        echo date('m-d-y', strtotime($eachdealtimeline['end_date']));
                                                    } else {
                                                        echo '00-00-00';
                                                    } ?>

                                                </td>
                                                <!-- <td><?php echo $eachdealtimeline['note'] ?></td> -->
                                                <!-- <td><?php if ($eachdealtimeline['link'] == 0) {
                                                                echo '';
                                                            } else {
                                                                echo $eachdealtimeline['link'];
                                                            }
                                                            ?>
                                                </td> -->
                                                <td><?php echo $eachdealtimeline['levelname']; ?></td>


                                            </tr>

                                <?php }
                                    }
                                } ?>

                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Attach change event to all date inputs
        $('input[type="date"]').on('change', function() {
            // Get the value from the input
            var inputValue = $(this).val();
            console.log(inputValue);
            // Get the ID to update the corresponding div
            var id = $(this).attr('id').split('_')[1]; // Extract dt_id from the input ID
            // Set the value to the corresponding div
            $('#myDiv_' + id).text(inputValue);

            // Call updateDate with the new value
            updateDate(inputValue, 'start_date', id);
        });

        // Optionally copy the initial value to the corresponding divs on page load
        $('input[type="date"]').each(function() {
            var id = $(this).attr('id').split('_')[1];
            $('#myDiv_' + id).text($(this).val());
        });
    });
</script>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })


        /* $('#enddate').change(function() {
             var s = $('#startdate').datepicker("getDate");
             var e = $('#enddate').datepicker("getDate");
             var diff = $('#startdate').datepicker("getDate") - $('#enddate').datepicker("getDate");


             var totalDays = diff / (1000 * 60 * 60 * 24) * -1;

             // Get the difference in whole weeks
             var wholeWeeks = totalDays / 7 | 0;
             // Estimate business days as number of whole weeks * 5
             var days = wholeWeeks * 5;
             console.log(days);
             // If not even number of weeks, calc remaining weekend days
             if (totalDays % 7) {
                 s.setDate(s.getDate() + wholeWeeks * 7);

                 while (s < e) {
                     s.setDate(s.getDate() + 1);

                     // If day isn't a Sunday or Saturday, add to business days
                     if (s.getDay() != 0 && s.getDay() != 6) {
                         ++days;
                     }
                 }
             }
             //$('#diff').text(days);
             document.getElementById("days").value = days;
         });*/

    });
    var x = [];
    var passedArray = <?php echo json_encode($dealtimelineData); ?>;
    for (var i = 0; i < passedArray.length; i++) {
        var ev = [{
            id: passedArray[i]['dt_id'],
            name: passedArray[i]['itemtypename'] + '  -  ' + passedArray[i]['item_description'],
            on: new Date(passedArray[i]['start_date']),
        }]
        x.push(ev[0]);
    }
    console.log(x);
    /*var tl = $('#myTimeline').jqtimeline({
         events: x,
         numYears: 1,
         startYear: 2022,
         click: function(e, event) {
             alert(event.name);
         }
     });*/
</script>
<script type="text/javascript">
    function OpenNewWindow(MyPath) {
        window.open(MyPath, "", "toolbar=no,status=no,menubar=no,location=center,scrollbars=no,resizable=no,height=500,width=1024");
    }
</script>

<script type="text/javascript">
    // $('#basic-datepicker').datepicker({
    //     dateFormat: 'yy-mm-dd',
    //     onClose: function(dateText, inst) {

    //         $(this).parent().find('.date').focus().html(dateText).blur();
    //         //location.reload(true);
    //         setTimeout(function() {
    //             location.reload();
    //         }, 1000);
    //     }
    // });

    // // Shows the datepicker when clicking on the content editable div
    // $('.date').click(function() {
    //     $(this).parent().find('.datepicker-input').datepicker("show");
    // });

    function updateDate(element, column, id) {
        if (column == 'start_date') {
            var value = element;
        } else {
            var value = element.innerText;
        }
        console.log(value + column + id);

        ///conole.log($(this).find(':selected').data('id'));
        $.ajax({
            url: '<?php echo base_url('Project/project_plan/updatedateformat') ?>',
            type: 'post',
            data: {
                value: value,
                column: column,
                id: id
            },
            success: function(data) {
                var obj = JSON.parse(data);

                console.log(obj);

                if (obj.status === 'OK') {
                    console.log('inside on condition');
                    if (column == 'duration' || column == 'start_day') {
                        location.reload(true);
                    }

                } else {
                    alert(obj.status, 'Something Went Wrong! Please contact Site Admin!');
                }
                location.reload(true);
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('request failed');
            }

        })

    }
</script>
<script>
    $("#button").click(function() {
        $("#leveldata").toggle();
    });

    function myFunction(element, id) {

        var value = element;
        console.log(value);
        let column = 'level';

        $.ajax({
            url: '<?php echo base_url('Project/project_plan/updatedateformat') ?>',
            type: 'post',
            data: {
                value: value,
                column: column,
                id: id
            },
            success: function(data) {
                var obj = JSON.parse(data);

                console.log(obj);

                if (obj.status === 'OK') {
                    console.log('inside on condition');
                    location.reload(true);


                } else {

                    alert('error', 'Something Went Wrong! Please contact Site Admin!');
                }
                location.reload(true);
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('request failed');
            }

        })

    };
</script>
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

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
        alert('Copied');
    }
</script>