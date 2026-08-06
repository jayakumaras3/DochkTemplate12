<div class="inbox-rightbar">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">
                <h6>Create New Category</h6>

                <form action="<?php echo base_url($form_link) ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-3">
                            <input type="text" name="description" class="form-control" placeholder="Category" value="" required="" />
                        </div>

                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-sm btn-primary">Create</button>
                        </div>

                        <?php if (isset($validation)) : ?>
                            <div class=col-12 col-sm-4>
                                <div class="alert alert-danger" role="alert">
                                    <?= $validation->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <p class="text-muted font-13 mb-4"></p>
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Categories</th>
                            <th># Courses</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $j = 0;
                        foreach ($categorydata  as $eachcategorydata) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo  $eachcategorydata['description']; ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($form_link_1) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="sc_mcid" value="<?php echo $eachcategorydata['sc_mcid'] ?>">
                                        <button type="submit" class="btn btn-outline-dark btn-xs waves-effect waves-light"><?php echo $eachcategorydata['catcount']; ?></button>
                                    </form>

                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($form_link_2) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="sc_mcid" value="<?php echo $eachcategorydata['sc_mcid'] ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                    </form>
                                </td>
                                <td>
                                    <?php

                                    $courseassigned = $eachcategorydata['catcount'];
                                    //echo $courseassigned;
                                    if ($courseassigned == 0) {
                                    ?>
                                        <form class="form-horizontal" action="<?php echo base_url($form_link_3) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="status" value="0">
                                            <input type="hidden" name="sc_mcid" value="<?php echo $eachcategorydata['sc_mcid'] ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    <?php
                                    } else {
                                        echo "No Del";
                                    }
                                    ?>
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