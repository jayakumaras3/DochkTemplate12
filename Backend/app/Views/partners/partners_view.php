<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($sub_header_link) ?>"><?php echo $sub_header; ?></a></li>
            
                </ol>
            </div>
            <h4 class="page-title"><?php echo $header; ?></h4>
        </div>
    </div>
</div>
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
<?php if (session()->get('success')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->get('success') ?>
    </div>
<?php endif; ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="inbox-leftbar">
                    <div class="mail-list mt-3">
                        <a href="<?php echo base_url('User_login/partners/partner_list'); ?>" class="list-group-item border-0"><i class="fe-anchor font-18 align-middle me-2"></i>Partners</a>
                        <a href="<?php echo base_url('Support/Support/admin_support'); ?>" class="list-group-item border-0"><i class="fe-smartphone font-18 align-middle me-2"></i>Support</a>
                    </div>
                </div>
                <div class="inbox-rightbar">

                    <div class="section-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="form-horizontal" action=" <?php echo base_url($form_link) ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                                            <div class="row mb-3">
                                                <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Partner Name</label>
                                                <div class="col-8 col-xl-9">
                                                    <input type="text" class="form-control col-md-12" name="partner_name" placeholder="Partner Name" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Location</label>
                                                <div class="col-8 col-xl-9">
                                                    <input type="text" class="form-control col-md-12" name="location" placeholder="Location" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Company Name</label>
                                                <div class="col-8 col-xl-9">
                                                    <input type="text" class="form-control col-md-12" name="company" placeholder="Company Name" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Email ID</label>
                                                <div class="col-8 col-xl-9">
                                                    <input type="text" class="form-control col-md-12" name="email_id" placeholder="Email ID" />
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Phone Number</label>
                                                <div class="col-8 col-xl-9">
                                                    <input type="text" class="form-control col-md-12" name="contact" placeholder="Phone Number" />
                                                </div>
                                            </div>


                                            <!-- <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Payment</label>
                            <div class="col-8 col-xl-9">
                                <select name="payment_type" class="form-control" id="payment_type">
                                    <option value="1">Company Pays</option>
                                    <option value="0">User Pays</option>
                                </select>
                            </div>
                        </div> -->
                                            <!-- <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">No of license</label>
                            <div class="col-8 col-xl-9">
                                <input type="number" class="form-control col-md-12" name="license" placeholder="License" />
                            </div>
                        </div> -->
                                            <!-- <div class="form-group col-md-12" id="rate_in_dollar_field">
                            <label>Rate in $</label>
                            <select class="form-select" name="discount">
                                <?php if (!empty($priceDetails)) {
                                    foreach ($priceDetails as $eachprice) { ?>
                                        <option value="<?php echo $eachprice['price'] ?>"><?php echo $eachprice['price'] ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div> -->

                                            <div class="justify-content-end row">
                                                <div class="col-8 col-xl-9">
                                                    <input type="hidden" name="current_client" value="<?php echo $client ?>" />
                                                    <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light">
                                                        <?php echo lang('Buttons.Create') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
</script>
<script>
    $(document).ready(function() {
        // Initially hide the Rate in $ field
        $('#rate_in_dollar_field').hide();

        // Attach change event listener to the Payment Type dropdown
        $('#payment_type').change(function() {
            // If the selected value is 1 (Company Pays), hide the Rate in $ field
            if ($(this).val() == 1) {
                $('#rate_in_dollar_field').hide();
            } else {
                // Otherwise, show the Rate in $ field
                $('#rate_in_dollar_field').show();
            }
        });
    });
</script>