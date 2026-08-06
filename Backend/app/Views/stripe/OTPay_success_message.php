<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more') ?>">Course Detail</a></li>
                    </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo $header; ?></h4>
        </div>
    </div>
</div>
<?php if (session()->get('success')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->get('success') ?>
    </div>
<?php endif; ?>
<?php if (session()->get('error')) : ?>
    <div class="alert alert-danger" role="alert">
        <?= session()->get('error') ?>
    </div>
<?php endif; ?>

<div class="section-body">
    <div class="row">
         <div class="card success-card col-12 col-sm-6 col-md-6 col-lg-4">
            <article class="card card-body">
                <div class="article-details">
                    <div class="course-title" style="text-align:center">
                        <!-- <h6>Importance of Data</h6> -->
                        <h5 class="truncated-name" data-fullname="Importance of Data" style="color:green" >
                            Payment Successful</h5>
                    </div>
                    <hr>
                    <div class="author-box-center">
                        <div class="author-box-job">
                            You are able to launch the course now. Please visit the Course Detail page to view the courses.
                            <div class="clearfix"></div><br />
                            <div class="article-cta">
                                <form action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                    <button id="checkout-and-portal-button" type="submit" class="btn btn-primary col-md-12">
                                        Course Detail
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
        </div>
    </div>
</div>
</div>