<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <form class="form-horizontal" action="<?php echo base_url('Holiday/holidays'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-select" name='show_year' id='show_year'>
                                <?php
                                $endyear = date('Y');
                                for ($i = $endyear; $i >= 2025; $i--) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="submit" class=" btn btn-outline-info btn-xs waves-effect waves-light" value="Show Holidays">
                        </div>
                    </div>
                </form>

            </div>
            <h4 class="page-title">Holidays | <?php echo $show_year ?></h4>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <?php $userlevel = session()->get('userlevel');
                $array  = explode(',', $userlevel);

                if (in_array("6", $array)  || in_array('2010', $array)) { ?>
                    <form action="<?php echo base_url('holiday/holidays/addholidays') ?>" method="POST" autocomplete="off"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-3">
                                <input id="birthday" name="holiday_dt" class="date-picker form-control" placeholder="Add Holiday" type="text" required="required" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>

                            <div class="col-lg-3">
                                <input type="text" class="form-control" name="description" placeholder="Description" required="" />
                            </div>
                            <div class="col-lg-2">
                                <select name="type" class="form-control">

                                    <option value="1" selected>Normal</option>
                                    <option value="2">Restricted</option>

                                </select>
                            </div>
                            <div class="col-lg-2">
                                <select name="country" class="form-control">

                                    <option value="1" selected>India</option>
                                    <option value="2">USA</option>

                                </select>
                            </div>

                            <div class="col-lg-2">
                                <div class="input-group">
                                    <button type="submit" name="holiday" class="btn btn-outline-primary waves-effect waves-light form-control">
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4>India Holidays</h4>
                <table class="table  table-sm ">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Holiday</th>
                            <?php if (in_array("6", $array) || in_array("4", $array)) { ?>

                                <th>Del</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($indiaholidaydata as $eachindiaholidaydata) {
                            $j = $j + 1; ?>
                            <tr>
                                <td class="center"><?php echo $j ?></td>
                                <td><?php echo date('Y-m-d', strtotime($eachindiaholidaydata['holiday_dt'])); ?></td>
                                <td><?php $type = $eachindiaholidaydata['type'];
                                    if ($type == 2) {
                                        echo 'Restricted';
                                    }
                                    ?></td>
                                <td><?php echo $eachindiaholidaydata['description'] ?></td>
                                <?php if (in_array("6", $array) || in_array("4", $array)) { ?>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('Holiday/Holidays/deleteholiday'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="id_hd" value="<?php echo $eachindiaholidaydata['id_hd'] ?>">
                                            <button onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4>US Holidays</h4>
                <div class="x_title">
                    <table class="table  table-sm ">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>Date</th>

                                <th>Holiday</th>
                                <?php if (in_array("6", $array) || in_array("4", $array)) { ?>
                                    <th>Del</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;
                            foreach ($usholidaydata as $eachusholidaydata) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td class="center"><?php echo $j ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($eachusholidaydata['holiday_dt'])); ?></td>

                                    <td><?php echo $eachusholidaydata['description'] ?></td>
                                    <?php if (in_array("6", $array) || in_array("4", $array)) { ?>
                                        <td>
                                            <form class="form-horizontal" action="<?php echo base_url('Holiday/Holidays/deleteholiday'); ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="id_hd" value="<?php echo $eachusholidaydata['id_hd'] ?>">
                                                <button onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>