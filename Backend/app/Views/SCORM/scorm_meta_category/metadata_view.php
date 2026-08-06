<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Categories</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url($form_link) ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="text" name="description" class="form-control" placeholder="Create <?php echo $form_title; ?>" value="" required="" />
                        </div>
                        <div class="col-lg-4">
                            <textarea type="text" name="details" class="form-control" placeholder="Description" value=""></textarea>
                        </div>
                        <div class="col-lg-4">
                            <input type="hidden" name="type" value="<?php echo $type; ?>">
                            <button type="submit" class="btn btn-outline-primary waves-effect waves-light">Create</button>
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

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th><?php echo $form_title; ?></th>
                            <th>Client</th>
                            <th># Courses</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $j = 0;
                        foreach ($metadata as $eachmetadata) {
                            $j = $j + 1;
                            // print_r( $eachmetadata);
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo  $eachmetadata['description'] ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($form_link_4) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="sc_mcid" value="<?php echo $eachmetadata['sc_mcid'] ?>">
                                        <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">+</button>
                                    </form>
                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($form_link_1) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="sc_mcid" value="<?php echo $eachmetadata['sc_mcid'] ?>">
                                        <button type="submit" class="btn btn-outline-dark btn-xs waves-effect waves-light">Courses</button>
                                    </form>

                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($form_link_2) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="sc_mcid" value="<?php echo $eachmetadata['sc_mcid'] ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                    </form>
                                </td>
                                <td>
                                  

                                        <form class="form-horizontal" action="<?php echo base_url($form_link_3) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="status" value="0">
                                            <input type="hidden" name="sc_mcid" value="<?php echo $eachmetadata['sc_mcid'] ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                             
                                </td>
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
</script>