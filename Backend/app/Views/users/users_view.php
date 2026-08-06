<?php $userlevel = session()->get('userlevel');
$arrayuserlevel  = explode(',', $userlevel);
$sessionclient = session()->get('client');
// print_r($clientlicense);
//if ($clientlicense[0]['user_count'] <  $clientlicense[0]['license']) {
//   $disablebutton = '';
//} else {
//    $disablebutton = 'style="display: none"';
//   $disablemsg =  '<p>Note: Maximum users reached. Kindly contact your admin</p>';
////}
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($main_header_link) ?>">Client</a></li>

                </ol>
            </div>
            <h4 class="page-title">Users List</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <?php if (isset($disablemsg)) {
                ?>
                    <div class="pull-left">
                        <?php echo $disablemsg; ?>
                    </div>

                <?php } else { ?>
                    <form class="form-horizontal" action="<?php echo base_url($addusers) ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="cid" value="<?php echo $clientid ?>">
                        <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light float-end">+ Add New user</button>
                    </form>
                <?php } ?>

                <h4 class="header-title mb-4"><?php echo lang('UI_Text.Active_Users') ?></h4>
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th><?php echo lang('UI_Text.Username') ?></th>
                            <th><?php echo lang('UI_Text.Name') ?></th>
                            <th>Access</th>
                            <th><?php echo lang('UI_Text.Last_Active') ?></th>
                            <?php if (in_array('6', $arrayuserlevel) || in_array('4', $arrayuserlevel)) { ?>
                                <th>Imper</th>
                            <?php } ?>
                            <th><?php echo lang('UI_Text.Edit') ?></th>
                            <th><?php echo lang('UI_Text.Delete') ?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($usertable)) : foreach ($usertable as $k) {
                                // $clientidd = array_map('intval', str_split($k['clientid']));
                                // if ($k['clientid'] == $clientid) { 
                        ?>
                                <tr>
                                    <td><?php echo ($k['username'] != '') ? $k['username'] : $k['email']; ?></td>
                                    <td><?php echo $k['name'] ?></td>
                                    <td title="<?php echo htmlspecialchars($k['userlevel'] ?? '', ENT_QUOTES); ?>">
                                            <?php
                                            $text = '';
                                            if (!empty($k['userlevel'])) {
                                                // Convert comma-separated string to array
                                                $levels = array_map('trim', explode(',', $k['userlevel']));

                                                // Remove "Normal Users" if it exists
                                                $levels = array_diff($levels, ['Normal']);

                                                // Convert back to string
                                                $text = implode(', ', $levels);
                                            }
                                            echo strlen($text) > 30
                                                ? substr($text, 0, 30) . '...'
                                                : $text;
                                            ?>
                                        </td>
                                    <td><?php echo ($k['dateandtime'] != '') ? date("m-d-Y", ($k['dateandtime'])) : ''; ?></td>
                                    <?php if (in_array('6', $arrayuserlevel) || in_array('4', $arrayuserlevel)) { ?>
                                        <!-- <td><a href="<?php echo base_url() . "Settings/settings/userImpersonate?id_user=" . $k['id_user'] ?>"></a></td> -->
                                        <td>
                                            <form action="<?php echo base_url('Settings/settings/userImpersonate') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="id_user" value=<?php echo  $k['id_user']; ?>>
                                                <button class="btn btn-outline-info waves-effect btn-xs waves-light"><span class="fa fa-random"></span> Imper</button>
                                            </form>
                                        </td>
                                    <?php } ?>
                                    <td><a href="<?php echo base_url($edit . '/' . $k['id_user'] . '/' . $k['clientid']) ?>"><button class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span> Edit</button></a></td>
                                    <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('Project_Manage/PM_users/deleteUsers/' . $k['id_user'] . '/' . $k['clientid']) ?>"><button class="btn btn-outline-danger waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span> Delete</button></a></td>
                                </tr>
                        <?php
                                //      }
                            }
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>