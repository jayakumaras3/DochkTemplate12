<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('demos/report') ?>">Report</a></li><b>&nbsp;>&nbsp;</b>
            <li class="active">Report Edit</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="x_panel">
        <div class="col-xs-12">
            <?php
            if ($cart_id == 'NotDefined') {
                echo "Details to be edited.";
            } else {
            ?>
                <!-- PAGE CONTENT BEGINS -->


                <div class="col-xs-12 col-sm-12">
                    <?php
                    $get_cart_details_array = isset($get_cart_details) ? $get_cart_details : '';
                    ?>
                    <div class="block block-drop-shadow">
                        <div class="content">
                            <div class="content controls">
                                <form action="<?php echo base_url('demos/updatecartdata') ?>" method="POST" autocomplete="off"><?= csrf_field() ?>
                                    <div class="form-row">
                                        <div class="col-md-5">
                                            Demo Comment:
                                        </div>
                                        <div class="col-md-7">
                                            <input class="form-control" name="comment" type="text" value="<?= isset($get_cart_details_array['comment']) ? $get_cart_details_array['comment'] : ''; ?>" />
                                        </div>
                                    </div><br>
                                    <div class="form-row">
                                        <div class="col-md-5">
                                            Demo Expiry Date:
                                        </div>
                                        <div class="form-group col-md-7">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
                                                <input required="required" autocomplete="off" type="text" id="demodate" name="demodate" class="datepicker form-control" value="<?= isset($get_cart_details_array['demodate']) ? date('m/d/Y', strtotime($get_cart_details_array['demodate'])) : ''; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-sm-5">
                                            <input type="hidden" name="updatecartdata" value="1">
                                            <input type="hidden" name="cart_id" value="<?= $cart_id; ?>">
                                            <div class="center">
                                                <button type="submit" class="btn btn-sm btn-warning btn-white">
                                                    <span class="bigger-110">Update</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!-- /.box -->
                </div><!-- /.col-->


            <?php } ?>
        </div>
    </div>
</div>

<script src="../assets/js/jquery.autosize.js"></script>