<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Income Tax View
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Financial Year</th>
                            <th>Download Form 16</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>1</td>
                            <td>2025-2026</td>
                            <td>
                                <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/downloadf16'); ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="taxyear" value="2026">
                                    <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-download"></span></button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>