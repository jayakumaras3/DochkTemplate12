<div class="col-md-12">
    <div class="x_panel">
        <table cellpadding="0" cellspacing="0" width="100%" class="table table-sm ">
            <thead>
                <tr>
                    <th width=5%>#</th>
                    <th>User</th>
                    <th>Designation</th>
                    <th>Role</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php $j = 0;
                foreach ($getuseraccess as $eachuseraccess) {
                    $j = $j + 1;  ?>
                    <tr>
                        <td width=5%><?php echo  $j ?></td>
                        <td><?php echo  $eachuseraccess['fullname'] ?></td>
                        <td><?php echo  $eachuseraccess['designation'] ?></td>
                        <td><?php echo  $eachuseraccess['accesslevel'] ?></td>
                        <td><?php echo  $eachuseraccess['email'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>