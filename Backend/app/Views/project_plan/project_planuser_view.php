<!doctype html>
<html lang="en-au">

<head>
    <title>DoChek</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>public/Landing/images/favicon.ico">
    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo base_url(); ?>/public/css/build/css/custom.min.css" rel="stylesheet">
    <link href="cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link href="<?php echo base_url(); ?>/public/css/stylesheets.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <!-- <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/datatables/jquery.dataTables.min.js'></script> -->

</head>
<style>
    tr[color_code='Delayed'] {
        color: red
    }

    .collapsible {

        color: white;
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.2);
        width: 100%;
        border: none;
        text-align: center;
        outline: none;
        font-size: 12px;
    }



    .tablescroll {
        height: 570px;
        overflow-y: scroll;
    }

    th {
        top: 0;
        position: sticky;
        background-color: skyblue;
    }
</style>
<?php
if ($dealtimelineData) {
} else {
    echo '<span style="color:white; padding: 10px;">Project Plan not yet ready.</span>';
    exit();
}
$course_id = $dealtimelineData[0]['fk_course_id'];
$gen_random = 420 . rand(25, 50) . rand(100, 1000);
$temp_id = password_hash($gen_random, PASSWORD_DEFAULT);
$dealCrypt = crypt($course_id, '');
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
    $course_id,
    $ciphering,
    $encryption_key,
    $options,
    $encryption_iv
);
$temp_id  =  preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $encryption . '_' . $temp_id);

$user_url = base_url('usergraph/projectplanGraph/' . $temp_id);

?>
<div class="col-md-12">
    <div class="content">
        <div class="x_panel">
            <div class="row">
                <h2 style="padding-left:1%"> Project Name : <?php echo $dealtimelineData[0]['projectname'] ?></h2>
                <form name="windowEvent" style="line-height: 12px;
     width: 10%;
     font-size: 8pt;
     font-family: tahoma;
     margin-top: 5px;
     margin-right: 20px;
     position:absolute;
     top:0;
     right:0;">
                    <input type="hidden" name="txtpath" value="<?php echo $user_url ?>" />
                    <input type="button" class="btn btn-info btn-sm form-control" value="View Graph" name="btnOpenPopup" onClick="OpenNewWindow(txtpath.value)" />
                </form>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tablescroll">
                        <table id="example1" class="table  table-sm table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Assign</th>
                                    <th>Item</th>
                                    <th>%</th>
                                    <th>Day</th>
                                    <th>Start date</th>
                                    <th>Days</th>
                                    <th>End date</th>
                                    <th>Note</th>
                                    <th>Link</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody><?php if ($headerdata) { ?>
                                    <?php foreach ($headerdata as $eachheaderdata) { ?>
                                        <tr>
                                            <td colspan="12">
                                                <div style="text-align:center">
                                                    <div class="btn btn-info btn-sm form-control"><?php echo $eachheaderdata['header_name'] ?>
                                                        <a href="#modal_default_<?php echo $eachheaderdata['id_ph'] ?>" data-toggle="modal" class="btn btn-sm btn-default"></a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php

                                            $j = 0;
                                            foreach ($dealtimelineData as $eachdealtimeline) {
                                                $j = $j + 1;
                                                if ($eachheaderdata['id_ph'] == $eachdealtimeline['header']) {
                                        ?>
                                                <tr color_code='<?php echo $eachdealtimeline['levelname'] ?>'>
                                                    <td><?php echo $eachdealtimeline['dt_id']  ?></td>
                                                    <td><?php echo $eachdealtimeline['itemtypename'] ?></td>
                                                    <td><?php echo $eachdealtimeline['item_description'] ?></td>
                                                    <td><?php echo $eachdealtimeline['completion'] ?></td>
                                                    <td><?php echo ($eachdealtimeline['start_day'] != '0') ? $eachdealtimeline['start_day'] : '' ?></td>
                                                    <td><?php echo date('d-M-y', strtotime($eachdealtimeline['start_date'])); ?></td>
                                                    <td><?php echo $eachdealtimeline['duration'] ?></td>
                                                    <td><?php if ($eachdealtimeline['end_date'] != '0000-00-00') {
                                                            echo date('d-M-y', strtotime($eachdealtimeline['end_date']));
                                                        } else {
                                                            echo '00-00-00';
                                                        } ?>

                                                    </td>
                                                    <td><?php echo isset($eachdealtimeline['note']) ? $eachdealtimeline['note'] : '' ?></td>
                                                    <td><?php if ($eachdealtimeline['link'] == 0) {
                                                            echo '';
                                                        } else {
                                                            echo $eachdealtimeline['link'];
                                                        }
                                                        ?>
                                                    </td>

                                                    <td><?php echo $eachdealtimeline['levelname']; ?></td>


                                                </tr>
                                <?php
                                                }
                                            }
                                        }
                                    } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- /.row -->
            <!-- /.row -->
        </div>
    </div>
</div>


</html>
<script type="text/javascript">
    function OpenNewWindow(MyPath) {
        window.open(MyPath, "", "toolbar=no,status=no,menubar=no,location=center,scrollbars=no,resizable=no,height=500,width=1024");
    }
</script>