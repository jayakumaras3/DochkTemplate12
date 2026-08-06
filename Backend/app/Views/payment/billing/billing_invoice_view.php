<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb mg-b-0">
      <li class="breadcrumb-item"><a href="<?php echo base_url('billing') ?>">Billing</a></li>
      <li class="breadcrumb-item active" aria-current="page">Invoice</li>
    </ol>
  </nav>
</div>

<div class="card card-body">
  <?php if (!empty($myBilling)) { ?>
    <div class="invoice">
      <div class="invoice-print">
        <div class="row">
          <div class="col-lg-12">
            <div class="invoice-title">
              <h2>Invoice</h2>
              <div class="invoice-number">Order #<?php echo $myBilling[0]['bill_id']; ?></div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <address>
                  <strong>Billed To:</strong><br>
                  <?php echo $myBilling[0]['fullname']; ?>
                </address>
              </div>
              <div class="col-md-6 text-end">
                <address>
                  <strong>Order Date:</strong><br>
                  Subscribed <?php echo date('d-m-Y', $myBilling[0]['createdon']); ?> to <?php echo date('d-m-Y', strtotime($myBilling[0]['expire_date'])); ?><br><br>
                </address>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="section-title">Order Summary</div>
          <div class="table-responsive">
            <table class="table table-striped table-md">
              <tr>
                <th data-width="40">#</th>
                <th>Item</th>
                <th class="text-center">Price</th>
                <th class="text-end">Total</th>
              </tr>

              <tr>
                <td>1</td>
                <td>Subscribed <?php echo date('d-m-Y', $myBilling[0]['createdon']); ?> to <?php echo date('d-m-Y', strtotime($myBilling[0]['expire_date'])); ?></td>
                <td class="text-center">USD <?php echo $myBilling[0]['amount']; ?></td>
                <td class="text-end">USD <?php echo $myBilling[0]['amount']; ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
  <hr>
</div>
</div>

</section>