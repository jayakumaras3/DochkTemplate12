<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                     <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header2_link) ?>"><?php echo $header2 ?></a></li>
                 
                </ol>
            </div>
            <h4 class="page-title"><?php echo $header3; ?></h4>
        </div>
    </div>
</div>
<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <h6>Update Scenario</h6>


            <form action="<?php echo base_url($edit_link) ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                <div class="mb-3">
                    <label><?php echo $scenario_details[0]['variable_description']; ?></label>
                </div><br>
                <div class="mb-3">
                    <label>Instructions : <?php echo $scenario_details[0]['instructions']; ?></label>
                </div><br>
                <div class="mb-3">
                    <label>Value</label>
                    <?php $input_variable_type = $scenario_details[0]['input_variable_type'];
                    if ($input_variable_type == 1) {
                        echo '<input type="text" name="value" class="form-control" value="' . $scenario_details[0]['value'] . '" required="" />';
                    } else {
                        $currentval = $scenario_details[0]['value'];
                        echo ' <select class="form-select" name="value">';
                        foreach ($getDropDownValues as $dropdown) {
                    ?>
                            <option value="<?php echo $dropdown['value']; ?>" <?php if ($currentval  == $dropdown['value']) echo "SELECTED"; ?>><?php echo $dropdown['text']; ?></option>
                    <?php
                        }
                        echo '</select>';
                    }
                    ?>
                </div><br>
                <div class="mb3">
                    <input type="hidden" name="xsis" value="<?php echo $scenario_details[0]['xsis']; ?>" required="" />
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                </div>
                <?php if (isset($validation)) : ?>
                    <div class=col-12 col-sm-4>
                        <div class="alert alert-danger" role="alert">
                            <?= $validation->listErrors() ?>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>