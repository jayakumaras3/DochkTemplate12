<?php if (session()->get('error')) :
    echo '<script>alert("' . session()->get('error') . '")</script>';
endif;
$client =  session()->get('client');
$arraystakeholders  = explode(',', $client);
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<style>
    .drag-handle tr {
        cursor: move;
    }

    .ui-sortable-placeholder {
        background: #f0f0f0;
        height: 40px;
        border: 2px dashed #ccc;
        visibility: visible !important;
    }
</style>



<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Ojts_consolidated/ojts_group_view') ?>">OJT Bundles</a></li>

                </ol>
            </div>
            <h4 class="page-title">Edit</h4>
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
        <?php $sequence = isset($rowojts_groupData[0]['sequence']) ? $rowojts_groupData[0]['sequence'] : 0; ?>
        <div class="card">
            <div class="card-body">
                <form id="addojtsForm" id="submitForm"><?= csrf_field() ?>
                    <div class="mb-2">
                        <select class="form-select select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" name="ojts_id[]" required="">
                            <?php foreach ($titles as $title) {
                                $key = array_search($title['ojts_id'], array_column($ojts_group_assigned, 'ojts_id'));
                                if (!empty($key) || $key === 0) {
                                } else {
                                    echo '<option value="' . $title['ojts_id'] . '">' . $title['filename'] . '</option>';
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="hidden" name="oj_group_id" value="<?php echo $oj_group_id ?>">
                        <input type="hidden" name="sequence" value=<?php echo $sequence + 1; ?>>
                        <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light submitButton">
                            Add OJTS
                        </button>
                    </div>
                </form>
            </div>
        </div>



    </div>


    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table  w-100">
                    <thead>
                        <tr>
                            <!-- <th></th> -->
                            <th>#</th>
                            <th>Title</th>
                            <th>Language</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="sortable">
                        <?php $j = 1;
                        foreach ($ojts_group_assigned as $assigned): ?>
                            <tr data-id="<?= $assigned['og_assign_id'] ?>">
                                <!-- <td class="drag-handle" style="cursor: move;">☰</td> Drag symbol -->
                                <td><?= $j++ ?></td>
                                <td><?= $assigned['filename'] ?></td>
                                <td><?= $assigned['language'] ?></td>
                                <td>
                                    <form action="<?= base_url('Others/Ojts_consolidated/assign_ojts_delete') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="oj_group_id" value="<?= $assigned['oj_group_id'] ?>">
                                        <input type="hidden" name="og_assign_id" value="<?= $assigned['og_assign_id'] ?>">
                                        <input type="hidden" name="status" value="0">

                                        <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')">
                                            <span class="mdi mdi-trash-can-outline"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</div>
</div>





<script>
    $('#addojtsForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addojtsForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('Others/Ojts_consolidated/assign_ojts_group') ?>',
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
    // Use event delegation or loop through all forms
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var button = form.querySelector('.submitButton'); // Use class instead of ID

            // Check if the button is already disabled (indicating the form is being submitted)
            if (button.disabled) {
                e.preventDefault(); // Prevent the form from being submitted again
                return false;
            }

            // Disable the submit button and change its text to 'Submitting...'
            button.disabled = true;
            button.innerHTML = 'Submitting...';
        });
    });
</script>

<!-- <script>
    $("#sortable").sortable({
        placeholder: "ui-sortable-placeholder",
        handle: ".drag-handle", // 👈 ONLY drag via this class
        update: function(event, ui) {
            let order = [];
            $('#sortable tr').each(function(index) {
                order.push({
                    id: $(this).data('id'),
                    position: index + 1
                });
            });

            console.log("Sending order to server:", order);

            $.ajax({
                url: "<?= base_url('Others/Ojts_consolidated/update_sequence') ?>",
                method: "POST",
                data: {
                    order: order,
                    <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                },
                success: function(response) {
                    console.log("Sequence updated", response);
                    location.reload(); // Optional: you can remove this if you want to update the UI without reloading
                },
                error: function(xhr) {
                    alert("AJAX error: " + xhr.responseText);
                }
            });
        }
    });
</script> -->