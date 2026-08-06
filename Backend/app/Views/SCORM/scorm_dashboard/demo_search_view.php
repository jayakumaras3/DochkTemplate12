<?php $userlevel = session()->get('userlevel');
$userlevelarray = explode(',', $userlevel);
?>
<?php if (session()->get('error')):
        // echo '<script>alert("' . session()->get('error') . '")</script>';
    endif;
    $client = session()->get('client');
    $arraystakeholders = explode(',', $client);

    ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"><?= lang('Buttons.Demos') ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-xl-3">
        <a href="<?php echo base_url('Demo/cart/addToCart/0') ?>" class="menu-link">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                <i class="fe-shopping-cart font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span
                                        data-plugin="counterup"><?php echo $cart_count[0]['cart_count'] ?></span></h3>
                                <p class="text-muted mb-1 text-truncate">Cart</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </a>
    </div> <!-- end col-->
    <div class="col-md-6 col-xl-3">
        <a href="<?php echo base_url('Demo/cart/report') ?>" class="menu-link">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-bar-chart-line- font-22 avatar-title text-info"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span
                                        data-plugin="counterup"><?php echo $getSalesReportCount[0]['salesReportCount'] ?></span>
                                </h3>
                                <p class="text-muted mb-1 text-truncate">Demo Report</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </a>
    </div> <!-- end col-->
</div>


<!-- <div class="row">
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('demo/Demo_dashboard/searchBycourseName'); ?>" method="post"
                    autocomplete="off"><?= csrf_field() ?>
                    <div class="mb-3">
                        <input type="text" placeholder="Enter Course Name" name="course_name" id="course_name"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Search By
                            Course Name</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body"> -->
                <!-- <form action="<?php echo base_url('demo/Demo_dashboard/searchBycourseCategory'); ?>" method="post" >
                    <div class="mb-3">
                        <select class="select2" multiple="multiple" tabindex="-1" style="width:100%" name="category[]"
                            required>
                            <?php if (!empty($categoryData)) { ?>
                                <option value="0"> - Select Category -</option>
                                <?php foreach ($categoryData as $eachcategoryData) { ?>
                                    <option value="<?php echo $eachcategoryData['sc_mcid'] ?>">
                                        <?php echo $eachcategoryData['description'] ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>


                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">Search By
                            Category</button>
                    </div>
                </form> -->
                <!-- <form action="<?php echo base_url('demo/Demo_dashboard/searchBycourseCategory'); ?>" method="post"
                    autocomplete="off"><?= csrf_field() ?>
                    <div class="mb-3">
                        <input type="text" placeholder="Enter Category" name="category" id="category"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">Search By
                            Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->
<?php if (isset($coursesDetails)) { ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <p class="text-muted font-13 mb-4"></p>
                <?php

                $j = 0; ?>
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Course Name</th>
                            <th>Duration</th>
                            <th>Categories</th>
                            <th>Cart</th>
                            <th>Details</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coursesDetails as $clienteachCourseddata) {

                            $j = $j + 1; ?>
                            <tr>
                                <td class="center"><?= $j ?></td>
                                <td><?php echo $clienteachCourseddata['course_name'] ?></td>
                                <td>
                                    <?php if ($clienteachCourseddata['duration'] > 0) { ?>
                                        <?php
                                        $duration = $clienteachCourseddata['duration'];
                                        if ($duration > 60) {
                                            $hours = intdiv($duration, 60);
                                            echo $hours . ' Hrs. ';
                                            $balancemin = $duration - $hours * 60;
                                            if ($balancemin > 0) {
                                                echo $balancemin . ' min';
                                            }
                                        } else {
                                            echo $duration . ' min';
                                        }

                                        ?></br>
                                    <?php } ?>
                                </td>
                                <!-- <td>
                                        <?php
                                        if (strlen($clienteachCourseddata['language'] ?? '') > 2) { ?>
                                            <?php echo $clienteachCourseddata['language'] ?>
                                        <?php } ?>
                                    </td> -->
                                <td>
                                    <?php if (strlen($clienteachCourseddata['demo_category'] ?? '') >= 2) { ?>
                                        <?php echo $clienteachCourseddata['demo_category'] ?>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php
                                    if (in_array('1', $arraystakeholders)) { // only TQ users access for Cart 
                                        ?>
                                        <div class=" btn-margin-custom">
                                            <a data-tooltip="Add To Cart"
                                                href="<?php echo base_url('Demo/cart/addToCart/' . $clienteachCourseddata['scourse_id']) ?>"><button
                                                    class="btn btn-outline-primary waves-effect btn-xs waves-light"><i class="mdi mdi-cart-outline"></i></button></a>
                                        </div>
                                    <?php }
                                    ?>
                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('my_training/read_more') ?>"
                                        method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="crid"
                                            value="<?php echo $clienteachCourseddata['scourse_id'] ?>">
                                        <?php if ($clienteachCourseddata['demo'] == 1) {
                                            echo '<input type="hidden" name="detail_type" value="3">';
                                        } else {
                                            echo ' <input type="hidden" name="detail_type" value="2">';
                                        } ?>
                                        <input type="hidden" name="tab" value="1">
                                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><i class="mdi mdi-eye-outline"></i></button>
                                    </form>
                                </td>
                                <?php
                        } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php

}
?>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "Select Category",
            allowClear: true
        });
    });
</script>