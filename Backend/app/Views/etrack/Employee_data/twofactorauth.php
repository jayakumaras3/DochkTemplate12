<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
               My Personal Data - Two Factor Authentication
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <div class="auth-brand">
                                    <a href="index.html" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            <img src="assets/images/logo-dark.png" alt="" height="22">
                                        </span>
                                    </a>

                                    <a href="index.html" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img src="assets/images/logo-light.png" alt="" height="22">
                                        </span>
                                    </a>
                                </div>
                                <p class="text-muted mb-4 mt-3">You are entering a secured area. Please authenticate using PAN Number. Please note the access to this page is recorded. No unauthorized entry allowed.</p>
                            </div>

                            <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/check_access'); ?>" method="POST"><?= csrf_field() ?>
                                <div class="mb-3">
                                    <input type="hidden" autocomplete="false" name="pannumber">
                                    <input class="form-control" type="text" required="" autocomplete="off" name="pannumber" placeholder="Enter your PAN number">
                                </div>
                                <div class="text-center d-grid">
                                    <button class="btn btn-outline-info btn-xs waves-effect waves-light" type="submit"> Authenticate Access </button>
                                </div>
                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
</div>