<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Admin HR Dashboard
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <?php if (in_array('2010', $arrayuserlevel)) { ?>
        

    <?php } ?>
</div>
</div>