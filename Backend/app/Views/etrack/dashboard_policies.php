<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Touchstone Policies
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Documents</th>
                            <th>Last Updated</th>
                            <th>Acceptance</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($policies as $pol) {
                            $j++;
                            echo '<tr>';
                            echo '<td>';
                            echo $j;
                            echo '</td>';
                            echo '<td>';
                            echo $pol['document_name'];
                            echo '</td>';
                            echo '<td>';
                            echo  date('d M Y', $pol['last_updated_on']);
                            echo '</td>';
                            echo '<td>';
                            if ($pol['accepted_on'] != '') {
                                echo date('d M Y', $pol['accepted_on']);
                            } else {
                                echo ' - ';
                            }   
                            echo '</td>';
                            echo '<td>';
                        ?>
                            <form class="form-horizontal" action="<?php echo base_url('etrack/dashboard/view_policy'); ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="emd_id" value="<?php echo $pol['emd_id']; ?>">
                                <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span></button>
                            </form>
                        <?php
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>