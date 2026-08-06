<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Ojts_consolidated/ojts_group_view') ?>">OJT Bundles</a></li>
                </ol>
            </div>
            <h4 class="page-title">OJT Bundles</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('Others/Ojts_consolidated/edit_group_name') ?>" method="post" autocomplete="off" id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <input type="text" name="group_name" class="form-control" placeholder="Bundle Name" value="<?php echo $oj_group_row[0]['group_name'] ?>" required maxlength="100" />
                        </div>
                        <div class="col-lg-12">
                            <input type="hidden" name="oj_group_id" value="<?php echo $oj_group_row[0]['oj_group_id'] ?>">
                            <button type="submit" class="btn btn-outline-warning  btn-xs waves-effect waves-light " id="submitButton">Update</button>
                        </div>
                    </div>
                    <?php if (isset($validation)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

</div>
</div>
</div>

<script>
    function target_popup1(url) {

        url = url.trim()
        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        newwin = window.open('http://' + url, 'windowname4', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }

    function target_popup2(filename, demoid) {

        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        newwin = window.open('http://purpleframetech.us/demos/upload/client/' + demoid + '/' + filename, 'windowname5', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }
    $(document).ready(function() {

        $('#dynamic-table').DataTable();

    });
</script>