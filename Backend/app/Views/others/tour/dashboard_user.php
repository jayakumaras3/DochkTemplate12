<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Active Tournaments</h4>
        </div>
    </div>
</div>
<div id="holder" class="row row-cols-1 row-cols-md-3 g-3 ">
    <?php
    foreach ($all_tournaments as $tour):  ?>

        <div class="col-md-12 col-lg-4 col-xl-3">
            <div class="card   ">
                <div class="card-body" style="text-align:left; ">
                    <form method="post" action="<?= base_url('Others/Tour_user/scores') ?>">
                        <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
                        <input type="hidden" name="tournament_name" value="<?= $tour['tournament_name'] ?>">

                        <button
                            style="border: none; border:0; padding-top: 5px; outline: none; background: none; width: 100%; text-align:center">
                            <div
                                style="display: box;
  display: flex;
  box-align: center;
  align-items: center;
  box-pack: center;
  justify-content: center;">
                                <?php $playimg = base_url('assets/assets/img/play.png'); ?>
                                <img class="img-fluid mx-auto d-block rounded"
                                    src="<?= base_url('assets/assets/img/tournament.jpg'); ?>"
                                    style="border: 1px solid transparent; display: block;background: none;  border-color: rgb(0, 0, 0, 0.2);  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);  height:150px;"
                                    alt="quiz">
                                <img style=" 
                                height: 40px;
                                width: 40px;
                                position: absolute;
                                opacity: 0.5;" src="<?php echo $playimg; ?>" alt="play" class="playBtn">
                            </div>


                        </button>

                    </form>
                    <div style="padding-left: 10px;">
                        <span class="badge bg-soft-info text-info p-1"><?php if ($tour['status'] == 1) {
                                                                                    echo 'Active';
                                                                                } else {
                                                                                    echo 'Editing';
                                                                                } ?></span>


                        <h5 class="font-12 my-1 sp-line-1">
                            <a class="text-dark" title="<?php echo 'Tournament' ?>"><?= $tour['tournament_name'] ?></a>
                        </h5>

                        <span class="font-10 text-muted">
                            Start Date :<?= $tour['start_date'] ?>
                            <br>
                            End Date :<?= $tour['end_date'] ?>
                        </span>

                    </div>
                </div>
            </div>
        </div>


    <?php endforeach; ?>

</div>