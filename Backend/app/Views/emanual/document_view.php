<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('Emanual/dashboard') ?>">
                            e-Manual Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                <?php //echo $sub_header_1;
                //  echo ' {';
                echo $e_manual_name[0]['product_name'];
                //  echo '}';
                ?>
            </h4>
        </div>
    </div>
</div>
<?php $userlevel = session()->get('userlevel');
$array  = array_map('intval', str_split($userlevel)); ?>


<div class="row row-cols-1 row-cols-md-3 g-3">
    <?php
    $j = 0;
    foreach ($getAssigndocument as $eachDocumentDetails) {
        $j = $j + 1;
        $docid = $eachDocumentDetails['emd_id'];
        $thump = $eachDocumentDetails['thumbnail'];
        if (strlen($thump) > 2) {
            $thumbnail = base_url('assets/assets/uploads/emanual_document_thumbnail/' . $docid . '/' . $thump);
        } else {
            $thumbnail = base_url('assets/assets/img/e-manual.jpg');
        }
    ?>
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card  ribbon-box">
                <?php $type = $eachDocumentDetails['type'];

                if ($type == 6) {
                ?>
                    <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_link/link') ?>" method="POST"><?= csrf_field() ?>
                    <?php
                }
                if ($type == 7) {
                    ?>
                        <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_link/trouble') ?>" method="POST"><?= csrf_field() ?>

                        <?php
                    } ?>
                        <input type="hidden" name="emd_id" value="<?php echo $eachDocumentDetails['emd_id'] ?>">


                        <button style="border: none;border:0; padding-top: 5px; outline: none; background: none;">
                            <div style="  background-color: #000; color: #fff;  opacity: .7;z-index:1;">
                                <img src="<?= $thumbnail ?>" style="display: block; width: 100%; " alt=" ">
                            </div>
                        </button>
                        </form>
                        <div class="card-body">
                            <h5 class=" my-1 sp-line-1"><a class="text-dark"><?php echo $eachDocumentDetails['document_name'] ?></a> </h5>
                        </div>
            </div>
        </div>


    <?php } ?>

</div>
</div>
</div>
</div>