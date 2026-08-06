
<script src="<?php echo base_url(); ?>/public/themes/acadian/assets/js/ckeditor.js"></script>

<style>
    .tagsvalue {
        background: #428bca;
        padding: 5px;
        border-radius: 2px;
        margin-right: 5px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li class="active">
                <a href="<?php echo base_url('demo_master') ?>">Demo Master</a>
            </li><b>&nbsp;>&nbsp;</b>
            <li class="active">
                Demo Details Edit
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php
        if ($demoid == 'NotDefined') {
            echo "No Demo details to be edited.";
        } else {
        ?>
            <!-- PAGE CONTENT BEGINS -->


            <div class="col-xs-12 col-sm-12">
                <div class="block block-drop-shadow">
                    <div class="content">
                        <div class="content controls">

                            <?php //inputbox($demoid, 2, 2); 
                            ?>
                            <!--Project Name-->
                            <div class="x_panel">

                                <div class="form-row">
                                    <div class="col-md-2">Project Name:</div>
                                    <form action="<?php echo base_url('demo_master/updatedata') ?>" method="POST" autocomplete="off"><?= csrf_field() ?>
                                        <div class="col-md-8">
                                            <input class="form-control" name="demoid" type="hidden" value="<?php echo $demoid ?>" />
                                            <input class="form-control" name="typeofval" type="hidden" value='3' />
                                            <?php if (!$project_name || count($project_name) <= 0) { ?>
                                                <input class="form-control" name="valid" type="hidden" value="na" />
                                                <input class="form-control" name="valdetails" type="text" value="na" />
                                            <?php } else {
                                                $valdetails = $project_name[0]["details"];
                                                $valid = $project_name[0]["id"]; ?>
                                                <input class="form-control" name="valid" type="hidden" value="<?php echo $valid ?>" />
                                                <input class="form-control" name="valdetails" type="text" value="<?php echo  $valdetails ?>" />
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="hidden" name="updatedata" value="1">
                                            <div class="center">
                                                <button type="submit" class="btn btn-warning btn-white">
                                                    <span class="bigger-110">Update</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><br>
                            <div class="x_panel">

                                <div class="form-row">
                                    <div class="col-md-2">Description:</div>

                                    <form action="<?php echo base_url('demo_master/updatedata') ?>" method="POST" autocomplete="off"><?= csrf_field() ?>
                                        <div class="col-md-8">
                                            <input class="form-control" name="demoid" type="hidden" value="<?php echo $demoid ?>" />
                                            <input class="form-control" name="typeofval" type="hidden" value='10' />
                                            <?php if (!$description || (count($description) <= 0)) { ?>
                                                <input class="form-control" name="valid" type="hidden" value="na" />
                                                <textarea class="ckeditor" cols="80" name="valdetails" rows="10">na</textarea>
                                            <?php } else {
                                                $valdetails = $description[0]["details"];
                                                $valid = $description[0]["id"]; ?>
                                                <input class="form-control" name="valid" type="hidden" value="<?php echo $valid ?>" />
                                                <textarea class="ckeditor" cols="80" name="valdetails" rows="10"><?php echo $valdetails ?></textarea>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="hidden" name="updatedata" value="1">
                                            <div class="center">
                                                <button type="submit" class="btn btn-warning btn-white">
                                                    <span class="bigger-110">Update</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div><br>
                            <?php //inputbox($demoid, 5, 1); 
                            ?>
                            <!-- Dont forget to uncommand-->
                            <div class="x_panel">

                                <div class="form-row">
                                    <div class="col-md-2">Course Link:</div>

                                    <form action="<?php echo base_url('demo_master/updatedata') ?>" method="POST" autocomplete="off"><?= csrf_field() ?>
                                        <div class="col-md-8">
                                            <input class="form-control" name="demoid" type="hidden" value="<?php echo $demoid ?>" />
                                            <input class="form-control" name="typeofval" type="hidden" value='6' />
                                            <?php if (!$course_link || (count($course_link) <= 0)) { ?>
                                                <input class="form-control" name="valid" type="hidden" value="na" />
                                                <input class="form-control" name="valdetails" type="text" value="na" />
                                            <?php } else {
                                                $valdetails = $course_link[0]["details"];
                                                $valid = $course_link[0]["id"]; ?>
                                                <input class="form-control" name="valid" type="hidden" value="<?php echo $valid ?>" />
                                                <input class="form-control" name="valdetails" type="text" value="<?php echo  $valdetails ?>" />
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="hidden" name="updatedata" value="1">
                                            <div class="center">
                                                <button type="submit" class="btn btn-warning btn-white">
                                                    <span class="bigger-110">Update</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div><br>
                            <div class="x_panel">

                                <div class="form-row">
                                    <div class="col-md-2">Showcase Link:</div>

                                    <form action="<?php echo base_url('demo_master/updatedata') ?>" method="POST" autocomplete="off"><?= csrf_field() ?>
                                        <div class="col-md-8">
                                            <input class="form-control" name="demoid" type="hidden" value="<?php echo $demoid ?>" />
                                            <input class="form-control" name="typeofval" type="hidden" value='4' />
                                            <?php if (!$showcase_link || count($showcase_link) <= 0) { ?>
                                                <input class="form-control" name="valid" type="hidden" value="na" />
                                                <input class="form-control" name="valdetails" type="text" value="na" />
                                            <?php } else {
                                                $valdetails = $showcase_link[0]["details"];
                                                $valid = $showcase_link[0]["id"]; ?>
                                                <input class="form-control" name="valid" type="hidden" value="<?php echo $valid ?>" />
                                                <input class="form-control" name="valdetails" type="text" value="<?php echo  $valdetails ?>" />
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="hidden" name="updatedata" value="1">
                                            <div class="center">
                                                <button type="submit" class="btn btn-warning btn-white">
                                                    <span class="bigger-110">Update</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div><br>

                            <?php //getcategory($demoid); 
                            ?>

                            <div class="x_panel">

                                <div class="form-row">
                                    <div class="col-md-2">Client Logo:</div>
                                    <form action="<?php echo base_url('demo_master/upload_demo_file') ?>" method="post" enctype="multipart/form-data" autocomplete="off"><?= csrf_field() ?>
                                        <div class="col-md-8">
                                            <small>*Upload images of size 100px by 100px, JPG file only.</small>
                                            <input type="hidden" name="demoid" value="<?php echo $demoid; ?>">
                                            <div class="input-group file">
                                                <!-- <input type="text" class="form-control" /> -->
                                                <input type="file" name="file" id="file" />
                                                <!-- <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button">Browse</button>
                                                </span> -->
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-warning btn-white">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php if (isset($filevalidation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $filevalidation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="x_panel">

                                <div class="form-row">
                                    <div class="col-md-2">Case Study:</div>
                                    <form action="upload_file_casestudy.php" method="post" enctype="multipart/form-data" autocomplete="off"><?= csrf_field() ?>
                                        <div class="col-md-8">
                                            <small>*Upload PDF files only.</small>
                                            <input type="hidden" name="demoid" value="<?php echo $demoid; ?>">
                                            <div class="input-group file">
                                                <!-- <input type="text" class="form-control" /> -->
                                                <input type="file" name="file" id="file" />
                                                <!-- <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button">Browse</button>
                                                </span> -->
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-warning btn-white">Upload</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <div class="x_panel">

                                <div class="form-row">
                                    <div class="col-md-2">Showcase Video:</div>
                                    <form action="upload_file_video.php" method="post" enctype="multipart/form-data" autocomplete="off"><?= csrf_field() ?>
                                        <div class="col-md-8">
                                            <small>*Upload .mp4 only.</small>
                                            <input type="hidden" name="demoid" value="<?php echo $demoid; ?>">
                                            <div class="input-group file">
                                                <!-- <input type="text" class="form-control" /> -->
                                                <input type="file" name="file" id="file" />
                                                <!-- <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button">Browse</button>
                                                </span> -->
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-warning btn-white"> Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                            if ($addcat) {
                                for ($i = 0; $i < count($addcat); $i++) {
                                    $id = $addcat[$i]["valie"];
                                    echo '<div class="x_panel">';

                                    echo '<div class="form-row">';
                                    echo '<div class="col-sm-2">' . $addcat[$i]['valuedesc'] . '</div>';
                                    echo '<form action="' . base_url('demo_master/category_process') . '" method="post" autocomplete="off"><?= csrf_field() ?>';
                                    echo '<div class="col-sm-6" >';
                                    echo '<select name="catlist"  class="form-control" >';
                                    $this->db = \Config\Database::connect();
                                    $qk = $this->db->query("SELECT * FROM typevaldescription where typevalid='$id' AND status = 1");
                                    $resultk = $qk->getResultArray();
                                    $num_rowsk = count($resultk);

                                    if (!$resultk || ($num_rowsk < 0)) {
                                        //return;
                                    }
                                    if ($num_rowsk == 0) {
                                        //return;
                                    }
                                    for ($j = 0; $j < $num_rowsk; $j++) {
                                        $ida = $resultk[$j]["id"];
                                        $valuedesc = $resultk[$j]["description"];
                                        echo '<option value=' . $ida . '>' . $valuedesc . '</option>';
                                    }

                                    echo '</select>';
                                    echo ' </div>';
                                    echo '<div class="col-sm-6">';
                                    echo '<input type="hidden" name="demoid" value="' . $demoid . '">';
                                    echo '<input type="hidden" name="addcattodemo" value="1">';
                                    echo '<button type = "submit" class = "btn btn-warning btn-white">Add Category</button>';
                                    echo '</div>';

                                    echo '</form>';
                                    //exit();
                                    echo '<div class="col-sm-3">';

                                    $qc = $this->db->query("SELECT details,id FROM integerval where typeofval = '9' and demoid = '$demoid' and status = 1");
                                    $result = $qc->getResultArray();
                                    $num_rows = count($result);
                                    // print_r($num_rows);
                                    //exit();
                                    if (!$result || ($num_rows < 0)) {
                                        return;
                                    }
                                    if ($num_rows == 0) {
                                        return;
                                    }
                                    for ($k = 0; $k < $num_rows; $k++) {
                                        $details = $result[$k]["details"];
                                        $integervalid = $result[$k]["id"];
                                        $qk = $this->db->query("SELECT description FROM typevaldescription where typevalid='$id' AND id='$details' AND status = 1");
                                        $resultk = $qk->getResultArray();
                                        $num_rowsk = count($resultk);
                                        if (!$resultk || ($num_rowsk < 0)) {
                                        }
                                        if ($num_rowsk == 0) {
                                        } else {
                                            $valuedesc = $resultk[0]["description"];
                                            echo '<span style="background-color:white" class="tagsvalue remove' . $integervalid . '">' . $valuedesc . ' <button type="button" class="close removetag" data-id="' . $integervalid . '" data-dismiss="alert" style="float: none;">×</button></span>';
                                        }
                                    }
                                    echo '</div>';
                                    echo '</div></div><br>';
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div><!-- /.box -->
            </div><!-- /.col-->
        <?php } ?>
    </div>
</div>





<script src="<?php echo base_url(); ?>/public/js/jquery.autosize.js"></script>

<script>
    $('.tagsvalue').on("click", ".removetag", function(e) { //user click on remove text links
        e.preventDefault();
        var removeXValue = $(this).data('id');
        // alert(removeXValue);   
        console.log('removefield : ' + removeXValue);

        if (confirm("Are you sure?")) {
            // your deletion code

            dataString = {
                remove_tag_id: removeXValue
            };

            $.ajax({
                url: '<?php echo base_url('demo_master/removetag') ?>',
                type: "POST",
                data: dataString,
                success: function(data) {

                    var obj = JSON.parse(data);
                    console.log(obj.status);

                    if (obj.status === 'success') {


                    } else if (obj.status === 'error') {

                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            });
            $('.remove' + removeXValue).remove();
        } else {
            return false;
        }


        //x--;
    });

    //CKEDITOR.replace( '.ckeditor' );
</script>