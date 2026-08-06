<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>Admin</li><b> &nbsp;> &nbsp;</b>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="block">
            <div class="x_panel">
                <h2>CLIENT SETTINGS</h2>
                <div class="x_title">
                    <div class="x_content">
                        <br />
                        <div class="content">
                            <table class="table  table-sm table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Stakeholders</th>
                                        <th>Users count</th>
                                        <th>Status</th>
                                        <th>Created on</th>
                                        <th>Settings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($clientData as $eachclient) { ?>
                                        <tr>
                                            <td><?php echo $eachclient['client_name'] ?></td>
                                            <td><?php echo  $eachclient['user_count'] ?></td>
                                            <td><?php echo ($eachclient['status'] == 1) ? 'Active' : 'Inactive'; ?></td>
                                            <td><?php echo date("m-d-Y", $eachclient['createdon']) ?></td>
                                            <td><button class="widget-icon  btn-dark"><a href="<?php echo base_url() . "/settings?cid=" . $eachclient['id_c']; ?>" ><span class="icon-cogs"></span></a></button></td>
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
</div>