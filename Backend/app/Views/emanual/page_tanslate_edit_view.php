<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <!-- <li><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li><b>&nbsp;>&nbsp;</b> -->
            <li><a href="<?php echo base_url($sub_header_1_link); ?>"><?php echo $sub_header_1; ?></a></li><b>&nbsp;>&nbsp;</b>
            <li><a href="<?php echo base_url($sub_header_2_link); ?>"><?php echo $sub_header_2; ?></a></li><b>&nbsp;>&nbsp;</b>
            <li class="active"><a href="<?php echo base_url($sub_header_3_link); ?>"><?php echo $sub_header_3; ?></a></li>&nbsp;>&nbsp;</b>
            <li class="active"><?php echo $sub_header_4; ?> <?php echo $row['page_name'] ?></li>
        </ol>
    </div>
</div>

<div class="col-md-4">
    <div class="block">
        <div class="content">
            <div class="x_panel">
                <div class="form-row">
                    <form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
                        <div class="col-md-12">
                            <div class="form-group col-md-12">
                                <label>Translate Page Name</label>
                                <input type="hidden" name="lang_id" value="<?= $lang_id ?>">
                                <input type="hidden" name="document_id" value="<?= $emd_id ?>">
                                <input type="hidden" name="page_id" value="<?= $row['empg_id'] ?>">
                                <input type="text" class="form-control col-md-12" name="page_name" placeholder="Page name" value="" />
                            </div>
                            <div class="form-group  col-md-12">
                                <?php if (isset($productditvalidation)) : ?>
                                    <div class=col-12 col-sm-4>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $productditvalidation->listErrors() ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <input type="hidden" name="user" value="">
                                <input type="hidden" name="empg_id" value="<?php echo $empg_id ?>">
                                <button type="submit" class="btn btn-warning btn-sm col-md-12">
                                    <i class="ace-icon fa fa-key bigger-110"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script>
    $('.fa').show();

    $('#uploadzipfile').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#uploadzipfile')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('SCORM/scorm_courses/scorm_upload') ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('.my_update_panel').html(data);

                    var obj = JSON.parse(data);

                    console.log(obj);

                    if (obj.status === 'OK') {
                        $('#loading_spinner').hide();
                        console.log('inside on condition');
                        //window.location.href = 'project_settings.php';
                        location.reload();
                        alert('File Uploaded Successfully');

                    } else {

                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                    }

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            })
        } else {
            message("Your Browser Don't support FormData API! Use IE 10 or Above!");
        }

    });
</script>
<script>
    $('#addcategoryForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addcategoryForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('SCORM/scorm_courses/assignmetacategory') ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function(data) {

                    var obj = JSON.parse(data);

                    console.log(obj);

                    if (obj.status === 'OK') {
                        console.log('inside on condition');
                        //window.location.href = 'project_settings.php';
                        location.reload();

                    } else {

                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                    }

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            })
        } else {
            message("Your Browser Don't support FormData API! Use IE 10 or Above!");
        }

    });
</script>
<script>
    $('#addmetadataForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addmetadataForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('SCORM/scorm_courses/assignmetacategory') ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function(data) {

                    var obj = JSON.parse(data);

                    console.log(obj);

                    if (obj.status === 'OK') {
                        console.log('inside on condition');
                        //window.location.href = 'project_settings.php';
                        location.reload();


                    } else {

                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                    }

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            })
        } else {
            message("Your Browser Don't support FormData API! Use IE 10 or Above!");
        }

    });
    $(document).ready(function() {

        $('#dynamic-table').DataTable();

    });
</script>