<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Ojts_consolidated/ojts_download_pdf'); ?>">OJTS Dashboard</a></li>
                </ol>
            </div>
            <h4 class="page-title">OJT Edit</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/updatefilenameojts') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <!-- Title Input -->
                        <div class="form-group col-md-6 mb-2">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="filenameInput" name="filename" placeholder="Title"
                                value="<?= esc($ojts_row[0]['filename'] ?? '') ?>" required maxlength="115" />
                            <div id="errorImage" style="display:none; margin-top:5px;">
                                <small class="text-danger">Title cannot exceed 115 characters.</small>
                            </div>
                        </div>

                        <script>
                            document.getElementById('filenameInput').addEventListener('input', function() {
                                const maxLength = this.getAttribute('maxlength');
                                const errorImage = document.getElementById('errorImage');

                                if (this.value.length >= maxLength) {
                                    errorImage.style.display = 'block';
                                } else {
                                    errorImage.style.display = 'none';
                                }
                            });
                        </script>


                        <!-- Language Dropdown -->
                        <div class="form-group col-md-4 mb-2">
                            <label>Language<span class="text-danger">*</span></label>
                            <select name="language" class="form-control">
                                <option value="English" <?php echo ($ojts_row[0]['language'] == 'English') ? 'Selected' : ''; ?>>English</option>
                                <option value="Spanish" <?php echo ($ojts_row[0]['language'] == 'Spanish') ? 'Selected' : ''; ?>>Spanish</option>
                                <option value="French" <?php echo ($ojts_row[0]['language'] == 'French') ? 'Selected' : ''; ?>>French</option>
                                <option value="Russian" <?php echo ($ojts_row[0]['language'] == 'Russian') ? 'Selected' : ''; ?>>Russian</option>
                                <option value="Portuguese" <?php echo ($ojts_row[0]['language'] == 'Portuguese') ? 'Selected' : ''; ?>>Portuguese</option>
                                <option value="Bahasa" <?php echo ($ojts_row[0]['language'] == 'Bahasa') ? 'Selected' : ''; ?>>Bahasa</option>
                                <option value="Arabic" <?php echo ($ojts_row[0]['language'] == 'Arabic') ? 'Selected' : ''; ?>>Arabic</option>
                                <option value="German" <?php echo ($ojts_row[0]['language'] == 'German') ? 'Selected' : ''; ?>>German</option>
                                <option value="Italian" <?php echo ($ojts_row[0]['language'] == 'Italian') ? 'Selected' : ''; ?>>Italian</option>
                                 <option value="Turkish" <?php echo ($ojts_row[0]['language'] == 'Turkish') ? 'Selected' : ''; ?>>Turkish</option>

                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group col-md-2 mt-3 mb-2">
                            <div class="w-100">
                                <?php if (isset($ojtsvalidation)) : ?>
                                    <div class="alert alert-white p-2 mb-2" role="alert">
                                        <?= $ojtsvalidation->listErrors() ?>
                                    </div>
                                <?php endif; ?>

                                <input type="hidden" name="ojts_id" value="<?php echo $ojts_row[0]['ojts_id'] ?>">

                                <button type="submit"
                                    class="btn btn-outline-warning btn-sm w-100"
                                    id="submitButton">
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    

    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/ojts_add') ?>" method="POST"><?= csrf_field() ?>
        <input type="hidden" name="ojts_id" value="<?php echo $ojts_id;  ?>">
        <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light float-end">+ Add Task Details</button>
    </form><br /><br />

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <?php $userlevel = session('userlevel');
                $arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? ''); ?>
                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <!-- <th width=5%>#</th> -->
                            <th>#</th>
                            <!-- <th>Filename</th> -->
                            <th>Task Title</th>
                            <th>Task</th>
                            <!-- <th>URL</th> -->
                            <!-- <th>Required</th> -->
                            <th>Edit</th>
                            <?php if (in_array('44', $arrayuserlevel)) { ?>
                                <th>Delete</th>
                            <?php } ?>

                    </thead>

                    <tbody class="row_position">
                        <?php $j = 0;
                        //  $nxt_page = 1;
                        foreach ($ojts_consolidatedData as $data) {
                            $j = $j + 1;
                        ?>
                            <tr>
                                <!-- <td widtd=5%><?php echo $j; ?></td> -->
                                <td><?php echo $j; ?></td>
                                <td><?php echo $data['title']; ?></td>
                                <!-- <td title="<?php echo $data['task']; ?>">
                                    <?php echo strlen($data['task']) > 50 ? substr($data['task'], 0, 50) . '...' : $data['task']; ?>
                                </td> -->
                                <td><?php echo $data['task']; ?></td>

                                <!-- <td><?php echo $data['link_url']; ?></td> -->
                                <!-- <td><?php echo $data['required']; ?></td> -->
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/ojts_edit') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="ojd_id" value="<?php echo $data['ojd_id']  ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                    </form>
                                </td>
                                <?php if (in_array('44', $arrayuserlevel)) { ?>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/ojts_delete') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ojd_id" value="<?php echo $data['ojd_id']  ?>">
                                            <input type="hidden" name="status" value="0">
                                            <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>