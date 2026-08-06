<?php

use CodeIgniter\I18n\Time;

$userlevel = session()->get('userlevel');
// \print_r( $userlevel);
$array  = array_map('intval', str_split($userlevel));
$clientid = session()->get('client');
$clientaarray = explode(",", $clientid);
$cid = $clientaarray[0];
$user = session()->get('id_user');
?>
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
        <div class="x_panel">

            <table  class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Datetime</th>
                        <th>Description</th>
                        <th>Link</th>
                        <th>Copy</th>
                        <th>Edit</th>
                        <th>Del</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $j = 0;
                    // echo '<pre>';
                    //  print_r($meetingagendaheader);
                    foreach ($meetingagendaheader as $eachMeetingAgendaHeader) {
                        $j = $j + 1;
                        isset($eachMeetingAgendaHeader['timezone_pname']) ? date_default_timezone_set($eachMeetingAgendaHeader['timezone_pname']) : '';
                        $datetime = new DateTime($eachMeetingAgendaHeader['start_date'] . ' ' . $eachMeetingAgendaHeader['time']);
                        $datetime->format('m-d-Y g:i A') . "\n";
                        $timezone_pname = session()->get('timezone_pname');
                        $la_time = new DateTimeZone($timezone_pname);
                        $datetime->setTimezone($la_time); ?>
                        <tr>
                            <td><?php echo $j ?></td>
                            <td><?php echo $datetime->format('m-d-Y g:i A'); ?><br>
                                <div id="demo_<?php echo $j ?>"></div>
                            </td>
                            <td><?php echo $eachMeetingAgendaHeader['description']; ?> </td>
                            <!-- <td style="width:40%;"><?php if ($eachMeetingAgendaHeader['meeting_link'] != '') { ?>
                                        <a href="<?php echo $eachMeetingAgendaHeader['meeting_link'] ?>">Click here to join the meeting</a>
                                    <?php  } else {
                                                        } ?>
                                </td> -->
                            <?php $pgen_random = 420 . rand(25, 50) . rand(100, 1000);
                            $temp_id = password_hash($pgen_random, PASSWORD_DEFAULT);
                            $pdealCrypt = crypt($eachMeetingAgendaHeader['id_m'], '');
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
                                $eachMeetingAgendaHeader['id_m'],
                                $ciphering,
                                $encryption_key,
                                $options,
                                $encryption_iv
                            );
                            $meeting_agenda_url = base_url('meeting_agenda_client?&temp_id=' . $encryption . '_' . $temp_id);
                            ?>
                            <td><a href="<?php echo $meeting_agenda_url ?>" target="_blank"><button type="submit" class="btn btn-sm btn-success"><span class="icon-link"></span></button></a></td>
                            <?php if ($user == $eachMeetingAgendaHeader['createdby']) { ?>
                                <td><a href="<?php echo base_url('meeting_agenda/copyagenda_header?projectid=' . $eachMeetingAgendaHeader['fk_project_id'] . '&id_m=' . $eachMeetingAgendaHeader['id_m']); ?>"><button type="submit" class="btn btn-sm btn-info"><span class="icon-copy"></span></button></a></td>
                                <td>
                                    <form action="<?php echo base_url('meeting_agenda/editagenda_header') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="projectid" value="<?php echo  $eachMeetingAgendaHeader['fk_project_id'] ?>">
                                        <input type="hidden" name="id_m" value="<?php echo $eachMeetingAgendaHeader['id_m'] ?>">
                                        <button type="submit" class="btn btn-sm btn-warning">
                                            <span class="icon-pencil"></span></button>
                                    </form>
                                </td>
                                <!-- <a href="<?php echo base_url('meeting_agenda/editagenda_header?projectid=' . $eachMeetingAgendaHeader['fk_project_id'] . '&id_m=' . $eachMeetingAgendaHeader['id_m']); ?>"><button type="submit" class="widget-icon btn-warning"><span class="icon-pencil"></span></button></a> -->
                                </td>
                                <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('meeting_agenda/delagenda_header?projectid=' . $eachMeetingAgendaHeader['fk_project_id'] . '&id_m=' . $eachMeetingAgendaHeader['id_m']); ?>"><button type="submit" class="btn btn-sm btn-danger"><span class="icon-trash"></span></button></a></td>
                            <?php } ?>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




<script>
    // function showpsttime(dttime, id) {
    //     const date = new Date(dttime);
    //     console.log(date);
    //     const pst = date.toLocaleString('en-US', {
    //         timeZone: 'America/Los_Angeles',
    //     });
    //     document.getElementById("demo_" + id).innerHTML = 'PST : ' + pst;

    // }
</script>