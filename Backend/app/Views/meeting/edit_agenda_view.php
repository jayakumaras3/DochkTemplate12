<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('meeting_agenda?projectid=' . $projectid) ?>">Back</a>
            </li><b>&nbsp;>&nbsp;</b>
            
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <?php $pgen_random = 420 . rand(25, 50) . rand(100, 1000);
            $temp_id = password_hash($pgen_random, PASSWORD_DEFAULT);
            $pdealCrypt = crypt($id_m, '');
            $ciphering = "AES-128-CTR";
            // Use OpenSSl Encryption method
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;

            // Non-NULL Initialization Vector for encryption
            $encryption_iv = '1234567891011121';

            // Store the encryption key
            $encryption_key = "GeeksforGeeks";

            // Use openssl_encrypt() function to encrypt the data
            $encryption = openssl_encrypt(
                $id_m,
                $ciphering,
                $encryption_key,
                $options,
                $encryption_iv
            );
            $meeting_agenda_url = base_url('meeting_agenda_client?&temp_id=' . $encryption . '_' . $temp_id);
            ?>
            <div class="x_panel">
                <label>
                    <a href="<?php echo $meeting_agenda_url ?>" target="_blank">
                        <h6>Meeting Agenda Link</h6>
                    </a>
                </label>
                <p id="p3" style="display:none"><?php echo $meeting_agenda_url ?></p>
                <button onclick="copyToClipboard('#p3')" class="btn btn-sm btn-primary">Copy</button>

                <a href="<?php echo base_url('meeting_agenda/export_meeting_agenda?projectid=' . $projectid . '&id_m=' . $id_m); ?>"><button class="btn btn-sm btn-danger">Export Meeting agenda</button></a>

            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="block">
            <div class="content">
                <div class="x_panel">
                    <form class="form" action="<?php echo base_url('meeting_agenda/add_attendees?projectid=' . $projectid . '&id_m=' . $id_m) ?>" method="POST"><?= csrf_field() ?>

                        <div class="form-group col-md-12">
                            <select class="select2" style="width: 100%;" multiple="multiple" tabindex="-1" name="attendees[]" required="">
                                <?php foreach ($projectusers as $projecteachusers) { ?>

                                    <option value="<?= $projecteachusers['name'] ?>"><?php  ?><?= $projecteachusers['name'] ?></option>
                                <?php } ?>
                            </select>

                        </div>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-warning btn-sm form-control">
                                <i class="ace-icon fa fa-key bigger-110"></i> Add Attendees
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-8">

        <div class="block">

            <div class="content">
                <div class="x_panel">
                    <form class="form" action="<?php echo base_url('meeting_agenda/updateagenda_header?projectid=' . $projectid . '&id_m=' . $id_m) ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-3">
                                <input id="birthday" name="start_date" class="date-picker form-control" placeholder="Start Date" type="text" required="required" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $row['start_date'] ?>">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class='input-group date' id='myDatepicker3'>
                                        <input type='text' class="form-control" name="time" value="<?php echo $row['time'] ?>" />
                                        <span class="input-group-addon">
                                            <span class="icon-time"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="meeting_link" placeholder="Meeting link" value="<?php echo $row['meeting_link'] ?>" />
                            </div>


                        </div><br />
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="description" placeholder="Description" value="<?php echo htmlspecialchars($row['description']) ?>" />
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-warning btn-sm form-control"> Update
                                </button>
                            </div>
                        </div>
                        <?php if (isset($validationData)) : ?>
                            <div class="col-md-12">
                                <div class="alert alert-white" role="alert">
                                    <?= $validationData->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="block">
            <div class="content">
                <div class="x_panel">
                    <form class="form" action="<?php echo base_url('meeting_agenda/addprojectstatus?projectid=' . $projectid . '&id_m=' . $id_m) ?>" method="POST"><?= csrf_field() ?>
                        <div class="row-md-4">
                            <label>Discussion : </label>
                            <input class="form-control" name="valid" type="hidden" />
                            <textarea class="ckeditor" name="discussion"></textarea>

                            <!-- <input type="text" class="form-control" name="project_status" placeholder="Topics for discussion" value="" /> -->
                        </div><br />
                        <div class="row-md-3">
                            <label>Completion Date : </label>
                            <input id="birthday" name="completion_dt" class="date-picker form-control" placeholder="Completion Date" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div><br />

                        <div class="row-md-4">
                            <label>Remarks : </label>
                            <input type="text" class="form-control" name="remarks" placeholder="Remarks" />
                        </div><br />
                        <div class="row-md-1">

                            <input type="hidden" class="form-control" name="id_m" value="<?php echo $id_m ?>" />
                            <input type="hidden" class="form-control" name="projectid" value="<?php echo $projectid ?>" />
                            <button type="submit" class="btn btn-info btn-sm form-control">
                                <i class="ace-icon fa fa-key bigger-110"></i> Add
                            </button>
                        </div>
                        <?php if (isset($validationagenda)) : ?>
                            <div class="col-md-12">
                                <div class="alert alert-white" role="alert">
                                    <?= $validationagenda->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="content">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Discussion</th>
                                <th>Comp Date</th>
                                <th>Remarks</th>
                                <th>Edit</th>
                                <th>Del</th>
                            </tr>
                        </thead>
                        <?php $j = 0;
                        foreach ($meetingagendadata as $eachmeetingagendadata) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j ?></td>
                                <td><?php echo $eachmeetingagendadata['project_status'] ?></td>
                                <td><?php if ($eachmeetingagendadata['completion_dt'] != '0000-00-00') {
                                        echo date('m-d-Y', strtotime($eachmeetingagendadata['completion_dt']));
                                    } else {
                                    } ?></td>
                                <td><?php echo $eachmeetingagendadata['remarks'] ?></td>
                                <td>
                                    <form action="<?php echo base_url('meeting_agenda/meeting_agenda_edit') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="id_ma" value="<?php echo $eachmeetingagendadata['id_ma'] ?>">
                                        <button type="submit" class="btn btn-sm btn-warning">
                                            <span class="icon-pencil"></span></button>
                                    </form>
                                    <!-- <a href="<?php echo base_url('meeting_agenda/meeting_agenda_edit?id_ma=' . $eachmeetingagendadata['id_ma']); ?>"><button type="submit" class="widget-icon btn-warning"><span class="icon-pencil"></span></button></a> -->
                                </td>
                                <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('meeting_agenda/delmeeting_agenda?projectid=' . $eachmeetingagendadata['fk_project_id'] . '&id_m=' . $eachmeetingagendadata['fk_id_m'] . '&id_ma=' . $eachmeetingagendadata['id_ma']); ?>"><button type="submit" class="btn btn-sm btn-danger"><span class="icon-trash"></span></button></a></td>

                            </tr>
                        <?php } ?>

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

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
        alert('Copied');
    }
</script>