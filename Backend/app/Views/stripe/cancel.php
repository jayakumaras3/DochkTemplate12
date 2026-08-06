<div class="section-header">
  <h1><?php echo $header; ?></h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="<?php echo base_url('billing') ?>">Billing</a></div>
    <!-- <div class="breadcrumb-item active"><?php //echo $header; ?></div> -->
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
    <div class="col-12 col-sm-6 col-md-6 col-lg-4">

      <article class="article">
        <div class="article-details">
          <div class="course-title">
            <!-- <h6>Importance of Data</h6> -->
            <h6 class="truncated-name" data-fullname="Importance of Data">
              Request for Cancllation</h6>
          </div>
          <hr>
          <div class="author-box-center">
            <div class="author-box-job">We are sorry to hear that you want to cancel the subscription. Request you to let us know the reason for cancellation. The cancellation will be completed within 10 working days. If you have any question please reach out to info@touchstonelc.com.<div class="clearfix"></div>
              <div class="article-cta">
                <form action="<?php echo base_url('Stripe/Cancel_subscription/createNewTicket') ?>" method="POST"><?= csrf_field() ?>
                  <div class="row">
                    <div class="col-md-12">
                      <textarea class="form-control" name="description" required></textarea>
                    </div>
                  </div>
                  <br>
                  <button id="checkout-and-portal-button" type="submit" class="btn btn-danger col-md-12"><a>Cancel Subscription</a></button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </article>
    </div>
  </div>
</div>