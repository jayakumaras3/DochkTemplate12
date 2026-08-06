<style>
    .list-div {
        display: inline-block;
        margin: 20px;
        vertical-align: top;
        /* Add this to align buttons properly */
    }


    ul.sortable {
        width: 100%;
        float: left;
        margin: 20px 0;
        list-style: none;
        position: relative !important;
    }

    ul.sortable li {
        cursor: move;
    }

    ul.sortable li.ui-sortable-helper {
        border-color: #3498db;
    }

    ul.sortable li.placeholder {
        height: 50px;
        background: #eee;
        border: 2px dashed #bbb;
        display: block;
        opacity: 0.6;
        border-radius: 2px;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
    }
</style>
<style>
    #videoContainer {
        width: 80%;
        height: 80%;
        display: flex;
        justify-content: left;
        align-items: left;
    }

    #videoElement {
        max-width: 80%;
        max-height: 80%;
    }
</style>
<?php $userlevel = session()->get('userlevel');
$userlevlarray  = explode(',', $userlevel); ?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url($sub_header_1_link); ?>"><?php echo $sub_header_1; ?></a></li><b>&nbsp;>&nbsp;</b>
            <li><a href="<?php echo base_url($sub_header_2_link); ?>"><?php echo $sub_header_2; ?></a></li><b>&nbsp;>&nbsp;</b>
            <li><a href="<?php echo base_url($sub_header_3_link); ?>"><?php echo $sub_header_3; ?></a></li><b>&nbsp;>&nbsp;</b>
            <li class="active"><?php echo $sub_header_4; ?></li>
        </ol>
    </div>
</div>
<?php if (session()->get('success')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->get('success') ?>
    </div>
<?php endif; ?><?php if (session()->get('error')) : ?>
    <div class="alert alert-danger" role="alert">
        <?= session()->get('error') ?>
    </div>
<?php endif; ?>
<?php if (session()->get('error')) : ?>
    <div class="alert alert-danger" role="alert">
        <?= session()->get('error') ?>
    </div>
<?php endif; ?>
<div class="col-md-12 col-sm-12 ">
    <?php

    $currentPage = isset($empg_id) ? $empg_id : '';
    $totalPages = $totalPages; // Replace with your actual total number of pages
    // Get the index of the current document ID
    $currentDocumentIndex = -1;
    foreach ($getAllpagedetails as $index => $pagedetail) {
        if ($pagedetail['document_id'] == $emd_id) {
            $currentDocumentIndex = $index;
            break;
        }
    }

    if ($currentDocumentIndex >= 0) {
        $currentDocument = $getAllpagedetails[$currentDocumentIndex];
        $db = \Config\Database::connect(); // Get the current document's page details
        $builder = $db->table('emanual_page as ep');
        $builder->select('ep.*');
        $builder->where('ep.document_id =', $currentDocument['document_id']);
        $pagedata = $builder->get()->getResultArray();

        $currentDocumentPageIds = array_column($pagedata, 'empg_id');

        // Find the current page's index
        $currentPageIndex = array_search($currentPage, $currentDocumentPageIds);

        // Calculate the previous and next page numbers within the same document
        $previousPage = ($currentPageIndex > 0) ? $currentDocumentPageIds[$currentPageIndex - 1] : null;
        $nextPage = ($currentPageIndex < count($currentDocumentPageIds) - 1) ? $currentDocumentPageIds[$currentPageIndex + 1] : null;

        echo '<p>Current Page: ' . $currentPage . '/' . $totalPages . '</p>';

        if ($previousPage !== null) {
            echo '<a href="pagecontent_translate?emd_id=' . $emd_id . '&empg_id=' . $previousPage . '&lang_id=' . $lang_id . '"><button class="btn btn-sm btn-info">Previous</button></a>';
        }
        if ($nextPage !== null) {
            echo '<a href="pagecontent_translate?emd_id=' . $emd_id . '&empg_id=' . $nextPage . '&lang_id=' . $lang_id . '"><button class="btn btn-sm btn-success">Next</button></a>';
        }
    } else {
        echo 'Invalid document ID';
    }
    ?>
</div>


<div class="x_panel">

    <?php foreach ($pagecontentdata as $order => $eachpagecontentdata) { //forloop of content display 
      //  print_r($eachpagecontentdata);
    ?>
        <div id="<?php echo $eachpagecontentdata['emc_id'] ?>">
            <?php if ($eachpagecontentdata['type'] == '96') {  // Image type 
            ?><div class="row">
                    <div class="col-md-6">
                        <?php if ($eachpagecontentdata['content1'] == '') { ?>
                        <?php } else { ?>
                            <div class="head bg-dot30 np tac">
                                <img src="<?php echo base_url() ?>/assets/uploads/emanual_image/<?php echo $eachpagecontentdata['page_id'] ?>/<?php echo $eachpagecontentdata['content1'] ?>" class="img-squre img-thumbnail" />
                            </div>
                        <?php } ?>
                    </div>
                    <?php if (in_array('83', $userlevlarray)) {
                        if ($eachpagecontentdata['status'] == '1' || $eachpagecontentdata['status'] == '5') { // Edit or delete or Ready for review state 	
                    ?>

                            <div class="col-md-1">
                                <div class="popup" data-toggle="modal" data-target=".bs-example-modal-lg<?= $eachpagecontentdata['emc_id'] ?>"><button class="btn btn-warning btn-sm" title="Translate"><span class="fa fa-book"></span></button></div>
                                <div class="modal fade bs-example-modal-lg<?= $eachpagecontentdata['emc_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myModalLabel">Translate Content</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="editpageUploadform" class="form-horizontal2" enctype="multipart/form-data"><?= csrf_field() ?>
                                                    <div class="col-md-3">
                                                        <input type="file" name="file" />
                                                        <input type="hidden" id="imagetype" name="type" value="" />
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
                                                        <input type="hidden" name="page_id" value="<?php echo $eachpagecontentdata['page_id'] ?>" />
                                                        <input type="hidden" name="lang_id" value="<?php echo $lang_id ?>" />
                                                        <input type="hidden" name="type" value="<?php echo $eachpagecontentdata['type'] ?>" />
                                                        <input type="hidden" name="status" value="<?php echo $eachpagecontentdata['status'] ?>" />
                                                        <button type="submit" class="btn btn-success btn-sm form-control">Upload</button>
                                                        <?php if (isset($thumbnailvalidation)) : ?>
                                                            <div class="col-md-12">
                                                                <div class="alert alert-danger" role="alert">
                                                                    <?= $thumbnailvalidation->listErrors() ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } ?>
                    <div class="col-md-5">
                        <?php if ($eachpagecontentdata['translate_content1'] == '') { ?>
                        <?php } else { ?>
                            <div class="head bg-dot30 np tac">
                                <img src="<?php echo base_url() ?>/assets/uploads/emanual_image/<?php echo $eachpagecontentdata['page_id'] ?>/<?php echo $eachpagecontentdata['translate_content1'] ?>" class="img-squre img-thumbnail" />
                            </div><br />
                        <?php } ?>
                    </div>
                </div>
            <?php } elseif ($eachpagecontentdata['type'] == '97') {  // video type 
            ?>
                <div class="row">
                    <div class="col-md-5">
                        <?php if ($eachpagecontentdata['content1'] == '') { ?>
                        <?php } else { ?>
                            <div id="videoContainer">
                                <video id="videoElement" controls>

                                    <?php $videoUrl =  base_url("assets/uploads/emanual_video/" . $empg_id . "/" . $eachpagecontentdata['content1']);
                                    ?>
                                    <source src="<?= $videoUrl ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if (in_array('83', $userlevlarray)) {
                        if ($eachpagecontentdata['status'] == '1' || $eachpagecontentdata['status'] == '5') { // Edit or delete or Ready for review state 	
                    ?>
                            <div class="col-md-1">
                                <div class="list-div">
                                    <div class="popup" data-toggle="modal" data-target=".bs-example-modal-lg<?= $eachpagecontentdata['emc_id'] ?>" title="Translate"><button class="btn btn-warning btn-sm"><span class="fa fa-book"></span></button></div>
                                    <div class="modal fade bs-example-modal-lg<?= $eachpagecontentdata['emc_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel">Translate Content</h5>
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="editvideoUploadform" class="form-horizontal2" enctype="multipart/form-data"><?= csrf_field() ?>
                                                        <div class="col-md-3">
                                                            <input type="file" name="file" />
                                                            <input type="hidden" id="videotype" name="type" value="" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
                                                            <input type="hidden" name="page_id" value="<?php echo $eachpagecontentdata['page_id'] ?>" />
                                                            <input type="hidden" name="lang_id" value="<?php echo $lang_id ?>" />
                                                            <input type="hidden" name="type" value="<?php echo $eachpagecontentdata['type'] ?>" />
                                                            <input type="hidden" name="status" value="<?php echo $eachpagecontentdata['status'] ?>" />
                                                            <button type="submit" class="btn btn-success btn-sm form-control">Upload</button>
                                                            <?php if (isset($thumbnailvalidation)) : ?>
                                                                <div class="col-md-12">
                                                                    <div class="alert alert-danger" role="alert">
                                                                        <?= $thumbnailvalidation->listErrors() ?>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                    <div class="col-md-6">
                        <?php if ($eachpagecontentdata['translate_content1'] == '') { ?>
                        <?php } else { ?>
                            <div id="videoContainer">
                                <video id="videoElement" controls>

                                    <?php $videoUrl =  base_url("assets/uploads/emanual_video/" . $empg_id . "/" . $eachpagecontentdata['translate_content1']);
                                    ?>
                                    <source src="<?= $videoUrl ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="list-div"><?php echo $eachpagecontentdata['content1'] ?></div>
                    </div>
                    <?php if (in_array('83', $userlevlarray)) {
                        if ($eachpagecontentdata['status'] == '1' || $eachpagecontentdata['status'] == '5' || $eachpagecontentdata['status'] == '3') { // Edit or delete or Ready for review state 	
                    ?>
                            <div class="col-md-1">
                                <div class="popup" data-toggle="modal" data-target=".bs-example-modal-lg<?= $eachpagecontentdata['emc_id'] ?>"><button class="btn btn-warning btn-sm" title="Translate"><span class="fa fa-book"></span></button></div>
                                <div class="modal fade bs-example-modal-lg<?= $eachpagecontentdata['emc_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myModalLabel">Translate Content</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php if ($eachpagecontentdata['type'] === '101') { ?>
                                                    <form method='post' class="editContentckeditorForm" data-formid="<?php echo $eachpagecontentdata['emc_id'] ?>"><?= csrf_field() ?>
                                                        <input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
                                                        <input type="hidden" name="page_id" value="<?php echo $eachpagecontentdata['page_id'] ?>" />
                                                        <input type="hidden" name="lang_id" value="<?php echo $lang_id ?>" />
                                                        <input type="hidden" name="type" value="<?php echo $eachpagecontentdata['type'] ?>" />
                                                        <input type="hidden" name="status" value="<?php echo $eachpagecontentdata['status'] ?>" />
                                                        <textarea type="text" name="editcontent" id="editcontent_<?php echo $eachpagecontentdata['emc_id'] ?>" class="ckeditor" placeholder="content"><?php echo $eachpagecontentdata['translate_content1'] ?></textarea>
                                                        <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                                    </form>
                                                <?php } else { ?>
                                                    <form method='post' class="editContentForm">
                                                        <input type="hidden" name="emc_id" value="<?php echo $eachpagecontentdata['emc_id'] ?>" />
                                                        <input type="hidden" name="page_id" value="<?php echo $eachpagecontentdata['page_id'] ?>" />
                                                        <input type="hidden" name="type" value="<?php echo $eachpagecontentdata['type'] ?>" />
                                                        <input type="hidden" name="status" value="<?php echo $eachpagecontentdata['status'] ?>" />
                                                        <input type="hidden" name="sequence" value="<?php echo $eachpagecontentdata['sequence'] ?>" />
                                                        <input type="hidden" name="lang_id" value="<?php echo $lang_id ?>" />
                                                        <?php if ($eachpagecontentdata['type'] === "88" || ($eachpagecontentdata['type'] === "89") || ($eachpagecontentdata['type'] === "90")) { ?>
                                                            <input type="text" class="form-control" name="content1" value="<?php echo $eachpagecontentdata['translate_content1'] ?>" />
                                                            <br />
                                                        <?php } else ?>
                                                        <?php if ($eachpagecontentdata['type'] === "91" || $eachpagecontentdata['type'] === "92" || $eachpagecontentdata['type'] === "93" || $eachpagecontentdata['type'] === "94" || $eachpagecontentdata['type'] === "95") { ?>
                                                            <textarea type="text" name="content1" class="form-control" placeholder="content" rows="8"><?php echo $eachpagecontentdata['translate_content1'] ?></textarea>
                                                            <br />
                                                        <?php } ?>
                                                        <?php if ($eachpagecontentdata['type'] === '101') { ?>
                                                            <textarea type="text" name="content1" class="ckeditor" placeholder="content" rows="8"><?php echo $eachpagecontentdata['translate_content1'] ?></textarea>
                                                            <br />
                                                        <?php } ?>
                                                        <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                                    </form>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                    <div class="col-md-5">
                        <?php echo $eachpagecontentdata['translate_content1'] ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<script>
    $('div[data-type="headers"]').hide();
    $('div[data-type="image"]').hide();
    $('div[data-type="video"]').hide();
    $('div[data-type="createTable"]').hide();

    $('div[data-type="contenttype"] select').on('change', (function() {
        var value = $('div[data-type="contenttype"] select option:selected').val();
        console.log(value);

        if (value === "88" || (value === "89") || (value === "90") || (value === "100")) {
            $('div[data-type="headers"]').show();
        } else {
            $('div[data-type="headers"]').hide();
        }

        if (value === "96") {
            $('div[data-type="image"]').show();
        } else {
            $('div[data-type="image"]').hide();
        }
        if (value === "97") {
            $('div[data-type="video"]').show();
        } else {
            $('div[data-type="video"]').hide();
        }
        if (value === "101") {
            $('div[data-type="createTable"]').show();
        } else {
            $('div[data-type="createTable"]').hide();
        }
        document.getElementById("headerstype").value = value;
        document.getElementById("imagetype").value = value;
        document.getElementById("videotype").value = value;
        document.getElementById("tabletype").value = value;
    }));


    $('.fa').show();

    $('.editContentForm').on('submit', function(event) {
        event.preventDefault();

        var dataString = new FormData(this); // Use 'this' to reference the current form element

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('Emanual/emanual_pagecontent/edittranslateContent'); ?>',
                type: "POST",
                data: dataString,
                processData: false, // Remove the 'async' and 'contentType' options
                contentType: false,
                success: function(data) {
                    $('.my_update_panel').html(data);
                    var obj = JSON.parse(data);
                    console.log(obj);
                    if (obj.status === 'OK') {
                        $('#loading_spinner').hide();
                        console.log('inside on condition');
                        location.reload();
                        // alert('Uploaded Successfully');
                    } else {
                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            });
        } else {
            message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
        }
    });
    $(document).on('submit', '.editContentckeditorForm', function(event) {
        event.preventDefault();

        var form = $(this);
        var formId = form.data('formid');
        var formData = new FormData(form[0]);
        var content1Value = CKEDITOR.instances['editcontent_' + formId].getData();
        formData.set('content1', content1Value);

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?= base_url('Emanual/emanual_pagecontent/edittranslateContent'); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('.my_update_panel').html(data);
                    var obj = JSON.parse(data);
                    console.log(obj);
                    if (obj.status === 'OK') {
                        $('#loading_spinner').hide();
                        console.log('inside on condition');
                        location.reload();
                        // alert('Uploaded Successfully');
                    } else {
                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            });
        } else {
            message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
        }
    });
    $('.editpageUploadform').on('submit', function(event) {
        event.preventDefault();

        var dataString = new FormData(this); // Use 'this' to reference the current form element

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('Emanual/emanual_pagecontent/edittranslatepageUpload'); ?>',
                type: "POST",
                data: dataString,
                processData: false, // Remove the 'async' and 'contentType' options
                contentType: false,
                success: function(data) {
                    $('.my_update_panel').html(data);
                    var obj = JSON.parse(data);
                    console.log(obj);
                    if (obj.status === 'OK') {
                        $('#loading_spinner').hide();
                        console.log('inside on condition');
                        location.reload();
                        alert('Uploaded Successfully');
                    } else if (obj.status === 'error') {
                        alert('File is already exists');
                        location.reload();
                    } else {
                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                        location.reload();
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            });
        } else {
            message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
        }
    });
    $('.editvideoUploadform').on('submit', function(event) {
        event.preventDefault();

        var dataString = new FormData(this); // Use 'this' to reference the current form element

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('Emanual/emanual_pagecontent/editvideoUpload'); ?>',
                type: "POST",
                data: dataString,
                processData: false, // Remove the 'async' and 'contentType' options
                contentType: false,
                success: function(data) {
                    $('.my_update_panel').html(data);
                    var obj = JSON.parse(data);
                    console.log(obj);
                    if (obj.status === 'OK') {
                        $('#loading_spinner').hide();
                        console.log('inside on condition');
                        location.reload();
                        alert('Uploaded Successfully');
                    } else if (obj.status === 'error') {
                        alert('File is already exists');
                        location.reload();
                    } else {
                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                        location.reload();
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            });
        } else {
            message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
        }
    });
    $('.reviewContentForm').on('submit', function(event) {
        event.preventDefault();

        var dataString = new FormData(this); // Use 'this' to reference the current form element

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('Emanual/emanual_pagecontent/readyforReview'); ?>',
                type: "POST",
                data: dataString,
                processData: false, // Remove the 'async' and 'contentType' options
                contentType: false,
                success: function(data) {
                    $('.my_update_panel').html(data);
                    var obj = JSON.parse(data);
                    console.log(obj);
                    if (obj.status === 'OK') {
                        $('#loading_spinner').hide();
                        console.log('inside on condition');
                        location.reload();
                        // alert('Uploaded Successfully');
                    } else if (obj.status === 'error') {
                        alert('File is already exists');
                        location.reload();
                    } else {
                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                        location.reload();
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            });
        } else {
            message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
        }
    });
    $('.approveContentForm').on('submit', function(event) {
        event.preventDefault();

        var dataString = new FormData(this); // Use 'this' to reference the current form element

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('Emanual/emanual_pagecontent/approveContent'); ?>',
                type: "POST",
                data: dataString,
                processData: false, // Remove the 'async' and 'contentType' options
                contentType: false,
                success: function(data) {
                    $('.my_update_panel').html(data);
                    var obj = JSON.parse(data);
                    console.log(obj);
                    if (obj.status === 'OK') {
                        $('#loading_spinner').hide();
                        console.log('inside on condition');
                        location.reload();
                        alert('Approved Successfully');
                    } else if (obj.status === 'error') {
                        // alert('File is already exists');
                        location.reload();
                    } else {
                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                        location.reload();
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            });
        } else {
            message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
        }
    });
    $('.rejectContentForm').on('submit', function(event) {
        event.preventDefault();

        var dataString = new FormData(this); // Use 'this' to reference the current form element

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('Emanual/emanual_pagecontent/rejectContent'); ?>',
                type: "POST",
                data: dataString,
                processData: false, // Remove the 'async' and 'contentType' options
                contentType: false,
                success: function(data) {
                    $('.my_update_panel').html(data);
                    var obj = JSON.parse(data);
                    console.log(obj);
                    if (obj.status === 'OK') {
                        $('#loading_spinner').hide();
                        console.log('inside on condition');
                        location.reload();
                        alert('Rejected');
                    } else if (obj.status === 'error') {
                        // alert('File is already exists');
                        location.reload();
                    } else {
                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                        location.reload();
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            });
        } else {
            message("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
        }
    });
</script>