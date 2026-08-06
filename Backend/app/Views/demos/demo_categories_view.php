<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <a href="<?php echo base_url('demos/view_category?searchval=2') ?>">Demo Dashboard</a><b>&nbsp;>&nbsp;</b>
            <li class="active">Create New Category</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="x_panel">
            <h6>Create New Category</h6>
            <div class="x_title">
                <div class="x_content">
                    <br />
                    <div class="block block-drop-shadow">
                        <div class="content controls">
                            <form action="<?php echo base_url('demos/addcategoryval') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                                <div class="form-row">
                                    <input type="text" name="category" class="form-control" placeholder="category" value="" required="" />
                                </div><br>
                                <div class="form-row">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="icon-key"> </i>Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="content">
                    <table id="dynamic-table" class="table  table-sm table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= lang('UI_Text.Type_of_Value') ?></th>
                                <th><?= lang('UI_Text.Action') ?></th>
                                <th><?= lang('UI_Text.Delete') ?></th>
                            </tr>
                        </thead>
                        <?php
                        foreach ($getcat as $geteachcat) {
                            $id = $geteachcat["valie"];
                            $valuedesc = $geteachcat["valuedesc"];

                            echo '<tr>';
                            echo '<td>';
                            echo $id;
                            echo '</td>';
                            echo '<td>';
                            echo $valuedesc;
                            echo '</td>';
                            echo '<td>';
                            echo '<form action="' . base_url("demos/typevalitem") . '" method="POST" autocomplete="off"><?= csrf_field() ?>';
                            echo '<input type="hidden" name="valid" value="' . $id . '">';
                            echo '<button type="submit" class="btn btn-sm btn-success"><span class="icon-th-list"></span></button>';
                            //echo '<button type="submit" class="btn btn-default btn-clean">View</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '<td>';
                            echo '<form action="category_process.php" method="POST" autocomplete="off"><?= csrf_field() ?>';
                            echo '<button type="submit" class="btn btn-sm btn-danger">';
                            echo '<input type="hidden" name="valid" value="' . $id . '">';
                            echo '<input type="hidden" name="delcat" value="1">';
                            echo '<span class="icon-trash"></span>';
                            echo '</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }


                        ?>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    function target_popup1(url) {

        url = url.trim()
        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        newwin = window.open('http://' + url, 'windowname4', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }

    function target_popup2(filename, demoid) {

        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        newwin = window.open('http://purpleframetech.us/demos/upload/client/' + demoid + '/' + filename, 'windowname5', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }
    $(document).ready(function() {

        $('#dynamic-table').DataTable();

    });
</script>