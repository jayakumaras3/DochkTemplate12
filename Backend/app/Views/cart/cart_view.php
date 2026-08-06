<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <!-- <li class="breadcrumb-item"><a href="<?php echo base_url('my_training'); ?>">Dashboard</a></li> -->
                    <?php if (strlen($header) > 3) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li>
                    <?php } ?>
              
                </ol>
            </div>
            <h4 class="page-title"><?php echo $sub_header_1; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title">Enter Client Details</h4>
                <form action="<?php echo base_url('Demo/cart/assignDemo') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                     <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">e-Mail</label>
                        <input type="text" name="email" class="form-control" placeholder="e-Mail" value="" required="" />
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Note {Limit 200 words}</label>
                        <input type="text" name="notes" class="form-control" placeholder="Note" value="" />
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Expiry date</label>
                        <input id="start_date" name="start_date" class="date-picker form-control" placeholder="Expiry Date" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo isset($row[0]['start_date']) ? $row[0]['start_date'] : '' ?>">
                        <script>
                            function timeFunctionLong(input) {
                                setTimeout(function() {
                                    input.type = 'text';
                                }, 60000);
                            }
                        </script>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Name</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($courseInCart  as $Cart) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $Cart['course_name']; ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Demo/cart/delItemFromCart') ?>" method="POST"><?= csrf_field() ?>
                                         <?= csrf_field() ?>
                                        <input type="hidden" name="cartid" value="<?php echo $Cart['cartid']; ?>">
                                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
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
    $(document).ready(function() {
        $('#dynamic-table').DataTable();
    });
</script>