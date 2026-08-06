<style>
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

    .contented {
        padding: 0 18px;
        display: none;
        overflow: hidden;
        background-color: rgb(118, 118, 118);

    }
</style>
<div class="page-title">
    <div class="title_left">
        <h3><?php echo $header ?></h3>
    </div>
    <div class="title_right">
        <div class="col-md-5 col-sm-5   form-group pull-right">
            <a href="<?php echo base_url($button_link) ?>">
                <button type="submit" class="btn btn-info btn-sm form-control">
                    <i class="ace-icon fa fa-key bigger-110"></i>+ Add Trainings
                </button>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">

    </div>
    <?php $userlevel = session()->get('userlevel');
    $array  = array_map('intval', str_split($userlevel)); ?>
    <div class="x_panel">

        <table id="dynamic-table" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width=5%>#</th>
                    <th>Trainings</th>
                    <th>Type</th>
                    <th>Users</th>
                    <th>Settings</th>
                    <th>Global</th>
                    <th>Edit</th>
            </thead>
            <tbody>
                <?php $j = 0;
                foreach ($getTraningData as $eachtrainingdata) {
                    $j = $j + 1; ?>
                    <tr>
                        <td><?php echo $j ?></td>
                        <td><?php echo $eachtrainingdata['name'] ?></td>
                        <td></td>
                        <td></td>
                        <td>
                            <form class="form-horizontal" action="<?php echo base_url($settings_link) ?>" method="POST"><?= csrf_field() ?>
                                 <?= csrf_field() ?>
                                <input type="hidden" name="training_id" value="<?php echo $eachtrainingdata['id'] ?>">
                                <input type="hidden" name="training_name" value="<?php echo $eachtrainingdata['name'] ?>">
                                <button type="submit" class="btn btn-sm widget-icon btn-info"><span class="icon-gear"></span></button>
                            </form>
                        </td>
                        <td></td>
                        <td>
                            <form class="form-horizontal" action="<?php echo base_url($edit_link) ?>" method="POST"><?= csrf_field() ?>
                                 <?= csrf_field() ?>
                                <input type="hidden" name="training_id" value="<?php echo $eachtrainingdata['id'] ?>">
                                <button type="submit" class="btn btn-sm widget-icon btn-warning"><span class="fa fa-pencil"></span></button>
                            </form>
                        </td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var contented = this.nextElementSibling;
            if (contented.style.display === "block") {
                contented.style.display = "none";
            } else {
                contented.style.display = "block";

            }
        });
    }
    $(document).ready(function() {

        $('#dynamic-table').DataTable();

    });
</script>