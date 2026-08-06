<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                e-Manual Dashboard
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <?php if ($productDetails != '') {
            if (count($productDetails) > 0) { ?>
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    <?php
                    foreach ($productDetails as $eachProductDetails) {
                        $em_id =  $eachProductDetails['em_id'];
                        $thump = $eachProductDetails['thumbnail'];
                        if (strlen(($thump > 2))) {
                            $thumbnail = base_url('assets/assets/uploads/emanual_thumbnail/' . $em_id . '/' . $thump);
                        } else {
                            $thumbnail = base_url('assets/assets/img/e-manual.jpg');
                        }

                    ?>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="card  ribbon-box">
                                <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/documents') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="em_id" value="<?php echo $eachProductDetails['em_id'] ?>">
                                    <button style="border: none;border:0; padding-top: 5px; outline: none; background: none;">
                                        <div style="  background-color: #000; color: #fff;  opacity: .7;z-index:1;">
                                            <img src="<?= $thumbnail ?>" style="display: block; width: 100%; " alt=" ">
                                        </div>
                                    </button>
                                </form>
                                <div class="card-body">
                                    <h4 class=" my-1 sp-line-1"><a class="text-dark"><?php echo $eachProductDetails['product_name'] ?></a> </h4>
                                    <p class="font-12 my-1 sp-line-1"><?php echo isset($eachProductDetails['description']) ? $eachProductDetails['description'] : '' ?> </p>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div> <!-- end col -->
        <?php
            }
        } ?>
    </div>
</div>