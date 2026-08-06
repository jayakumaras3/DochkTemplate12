<?php
function my_simple_crypt($string, $action = 'e')
{
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'e') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if ($action == 'd') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}
?>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li><b>&nbsp;>&nbsp;</b>
			<li class="active"><?php echo $sub_header_1; ?></li>
		</ol>
	</div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <table id="dynamic-table" class="table table-sm  table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Comment</th>
                        <th>PassCode</th>
                        <th>Attempts</th>
                        <th>Expiry</th>
                        <th>Link</th>
                        <th>Edit</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $j = 0;
                    foreach ($showreport as $showeachreport) {
                        $j = $j + 1;
                        echo "<tr><td width='10%'>";
                        echo $j;
                        echo "</td><td width='10%'>";
                        echo $showeachreport['uid'];
                        echo "</td><td min-width='10%'>";
                        echo $showeachreport['comment'];
                        echo "</td><td width='10%'>";
                        echo $showeachreport['keyval'];
                        echo "</td><td width='10%'>";
                        echo $showeachreport['status'];
                        echo "</td><td width='10%'>";
                        echo $showeachreport['demodate'];
                        echo "</td><td width='10%'>";
                        echo '<a  style="" href="' . 'view_report_schedule?us43k=' . my_simple_crypt($showeachreport['uid'], 'e') . '&jdick18=' . my_simple_crypt($showeachreport['keyval'], 'e') . '" target="_blank"><button class="btn btn-sm btn-success"><i class="icon-th-list"></i></button></a> ';
                        echo "</td><td width='10%'>";
                        echo '<a  style="" href="' . 'edit_date?cart_id=' . $showeachreport['cartod'] . '" target="_blank"><button class="btn btn-sm btn-warning"><i class="icon-pencil"></i></button></a> ';
                        echo "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#dynamic-table').DataTable();

    });
</script>